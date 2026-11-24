<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\MengelolaSuara;
use App\Models\Kategori;
use App\Models\Suara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuaraController extends Controller
{
    use MengelolaSuara;

    public function index()
    {
        $daftar = auth()->user()->suara()->with('kategori')->latest()->paginate(10);

        return view('suara.index', compact('daftar'));
    }

    public function create()
    {
        $kategori = Kategori::orderBy('nama')->get();

        return view('suara.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $this->validatorSuara($request)->validate();

        $anonim = $request->boolean('is_anonim');
        $kode   = $anonim ? $this->buatKodeKelola() : null;

        $suara = Suara::create([
            'nomor'           => $this->buatNomor(),
            'kode_kelola'     => $anonim ? Hash::make($kode) : null,
            'user_id'         => $anonim ? null : auth()->id(), // anonim: tidak ada jejak akun
            'kategori_id'     => $request->kategori_id,
            'jenis'           => $request->jenis,
            'judul'           => $request->judul,
            'isi'             => $request->isi,
            'is_anonim'       => $anonim,
            'ingin_publikasi' => $request->boolean('ingin_publikasi'),
        ]);

        $suara->riwayat()->create(['status' => 'diajukan']);
        $this->simpanLampiran($request, $suara);

        if ($anonim) {
            // Sengaja tanpa redirect: kode kelola tidak boleh disimpan di session
            return view('suara.terkirim-anonim', ['nomor' => $suara->nomor, 'kode' => $kode]);
        }

        return redirect()->route('suara.show', $suara)->with('sukses', 'Suara berhasil dikirim.');
    }

    public function show(Suara $suara)
    {
        $this->pastikanPemilik($suara);

        $suara->load(['kategori', 'lampiran', 'riwayat']);

        return view('suara.show', compact('suara'));
    }

    public function edit(Suara $suara)
    {
        $this->pastikanPemilik($suara);
        $this->pastikanBisaDikelola($suara);

        $suara->load('lampiran');
        $kategori = Kategori::orderBy('nama')->get();

        return view('suara.ubah', compact('suara', 'kategori'));
    }

    public function update(Request $request, Suara $suara)
    {
        $this->pastikanPemilik($suara);
        $this->pastikanBisaDikelola($suara);

        $this->validatorSuara($request, $suara)->validate();
        $this->terapkanPerubahan($request, $suara);

        return redirect()->route('suara.show', $suara)->with('sukses', 'Suara diperbarui.');
    }

    // Batalkan: langsung, selama belum disetujui
    public function batalkan(Suara $suara)
    {
        $this->pastikanPemilik($suara);
        $this->pastikanBisaDikelola($suara);

        $suara->delete(); // soft delete

        return redirect()->route('suara.index')->with('sukses', 'Pengajuan dibatalkan.');
    }

    // Ajukan penghapusan: untuk suara yang sudah disetujui, menunggu keputusan pengelola
    public function ajukanHapus(Request $request, Suara $suara)
    {
        $this->pastikanPemilik($suara);

        abort_unless($suara->status === 'disetujui', 403);

        if ($suara->hapus_diminta_at) {
            return back()->with('sukses', 'Permintaan penghapusan sudah dikirim sebelumnya.');
        }

        $data = $request->validate([
            'alasan_hapus' => ['nullable', 'string', 'max:500'],
        ]);

        $suara->update([
            'hapus_diminta_at' => now(),
            'alasan_hapus'     => $data['alasan_hapus'] ?? null,
        ]);

        return back()->with('sukses', 'Permintaan penghapusan dikirim ke pengelola.');
    }

    // Hanya pemilik. Suara anonim (user_id kosong) otomatis tertolak di sini.
    private function pastikanPemilik(Suara $suara): void
    {
        abort_unless((int) $suara->user_id === (int) auth()->id(), 403);
    }

    private function pastikanBisaDikelola(Suara $suara): void
    {
        abort_unless($suara->bisa_dikelola, 403, 'Suara yang sudah disetujui tidak bisa diubah atau dibatalkan langsung.');
    }

    private function buatNomor(): string
    {
        do {
            $nomor = 'SRA-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));
        } while (Suara::withTrashed()->where('nomor', $nomor)->exists());

        return $nomor;
    }
}