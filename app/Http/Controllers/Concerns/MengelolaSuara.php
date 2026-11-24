<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Suara;
use App\Models\SuaraRiwayat;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

trait MengelolaSuara
{
    protected function validatorSuara(Request $request, ?Suara $suara = null)
    {
        $validator = Validator::make($request->all(), [
            'jenis'            => ['required', 'in:keluhan,aspirasi,pengalaman'],
            'kategori_id'      => ['required', 'exists:kategori,id'],
            'judul'            => ['required', 'string', 'max:150'],
            'isi'              => ['required', 'string', 'min:20'],
            'is_anonim'        => ['nullable', 'boolean'],
            'ingin_publikasi'  => ['nullable', 'boolean'],
            'lampiran'         => ['nullable', 'array'],
            'lampiran.*'       => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'hapus_lampiran'   => ['nullable', 'array'],
            'hapus_lampiran.*' => ['integer'],
        ]);

        // Total lampiran (yang tersisa + yang baru) maksimal 3
        $validator->after(function ($v) use ($request, $suara) {
            $hapus = array_map('intval', (array) $request->input('hapus_lampiran', []));
            $sisa  = $suara ? $suara->lampiran()->whereNotIn('id', $hapus)->count() : 0;
            $baru  = count(Arr::wrap($request->file('lampiran', [])));

            if ($sisa + $baru > 3) {
                $v->errors()->add('lampiran', 'Total lampiran maksimal 3 file.');
            }
        });

        return $validator;
    }

    protected function simpanLampiran(Request $request, Suara $suara): void
    {
        $urutan = $suara->lampiran()->count();

        foreach (Arr::wrap($request->file('lampiran', [])) as $file) {
            $urutan++;
            $suara->lampiran()->create([
                'path'      => $file->store('lampiran', 'public'),
                'nama_asli' => $suara->is_anonim
                    ? 'lampiran-' . $urutan . '.' . $file->extension() // nama netral untuk anonim
                    : $file->getClientOriginalName(),
            ]);
        }
    }

    protected function terapkanPerubahan(Request $request, Suara $suara): void
    {
        DB::transaction(function () use ($request, $suara) {
            $suara->update([
                'jenis'           => $request->jenis,
                'kategori_id'     => $request->kategori_id,
                'judul'           => $request->judul,
                'isi'             => $request->isi,
                'ingin_publikasi' => $request->boolean('ingin_publikasi'),
            ]);

            $hapus = array_map('intval', (array) $request->input('hapus_lampiran', []));
            foreach ($suara->lampiran()->whereIn('id', $hapus)->get() as $lampiran) {
                Storage::disk('public')->delete($lampiran->path);
                $lampiran->delete();
            }

            $this->simpanLampiran($request, $suara);

            // Suara yang pernah ditolak lalu diperbaiki otomatis diajukan ulang
            if ($suara->status === 'ditolak') {
                $suara->update(['status' => 'diajukan']);
                SuaraRiwayat::where('suara_id', $suara->id)->where('status', 'ditolak')->delete();
                SuaraRiwayat::where('suara_id', $suara->id)->where('status', 'diajukan')->update(['updated_at' => now()]);
            }
        });
    }

    protected function buatKodeKelola(): string
    {
        $huruf = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // tanpa I, O, 0, 1 agar tidak membingungkan
        $kode  = '';
        for ($i = 0; $i < 8; $i++) {
            $kode .= $huruf[random_int(0, strlen($huruf) - 1)];
        }

        return $kode;
    }
}