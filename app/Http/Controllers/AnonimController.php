<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\MengelolaSuara;
use App\Models\Kategori;
use App\Models\Suara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AnonimController extends Controller
{
    use MengelolaSuara;

    public function form()
    {
        return view('anonim.form');
    }

    public function buka(Request $request)
    {
        $suara = $this->verifikasi($request);

        if (! $suara->bisa_dikelola) {
            throw ValidationException::withMessages([
                'nomor' => 'Suara ini sudah disetujui pengelola sehingga tidak bisa diubah atau dibatalkan lagi. Hubungi pengelola jika ingin menghapusnya.',
            ]);
        }

        return $this->tampilkan($request, $suara);
    }

    public function simpan(Request $request)
    {
        $suara = $this->verifikasi($request);
        abort_unless($suara->bisa_dikelola, 403);

        $validator = $this->validatorSuara($request, $suara);

        if ($validator->fails()) {
            // Tampilkan ulang formulir tanpa redirect, supaya isian tidak masuk session dan kode tidak perlu diketik ulang
            $suara->fill($request->only(['jenis', 'kategori_id', 'judul', 'isi']));
            $suara->ingin_publikasi = $request->boolean('ingin_publikasi');

            return $this->tampilkan($request, $suara)->withErrors($validator);
        }

        $this->terapkanPerubahan($request, $suara);

        return redirect()->route('anonim.form')->with('sukses', 'Suara anonim berhasil diperbarui.');
    }

    public function batal(Request $request)
    {
        $suara = $this->verifikasi($request);
        abort_unless($suara->bisa_dikelola, 403);

        $suara->delete();

        return redirect()->route('anonim.form')->with('sukses', 'Suara anonim dibatalkan.');
    }

    // Nomor + kode diperiksa ulang di setiap permintaan; tidak ada yang disimpan di session
    private function verifikasi(Request $request): Suara
    {
        $request->validate([
            'nomor'       => ['required', 'string'],
            'kode_kelola' => ['required', 'string'],
        ]);

        $suara = Suara::where('nomor', strtoupper(trim($request->nomor)))
            ->where('is_anonim', true)
            ->first();

        $kode = strtoupper(trim($request->kode_kelola));

        if (! $suara || ! $suara->kode_kelola || ! Hash::check($kode, $suara->kode_kelola)) {
            throw ValidationException::withMessages(['nomor' => 'Nomor atau kode kelola salah.']);
        }

        return $suara;
    }

    private function tampilkan(Request $request, Suara $suara)
    {
        $suara->load(['kategori', 'lampiran', 'riwayat']);

        return view('anonim.ubah', [
            'suara'    => $suara,
            'kategori' => Kategori::orderBy('nama')->get(),
            'kode'     => strtoupper(trim($request->kode_kelola)),
        ]);
    }
}