<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Suara;
use App\Models\SuaraRiwayat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengelolaSuaraController extends Controller
{
    public function dashboard()
    {
        $hitung = Suara::query()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');
        $tayang = Suara::where('status', 'disetujui')->where('ingin_publikasi', true)->count();
        $mintaHapus = Suara::whereNotNull('hapus_diminta_at')->count();
        $terbaru = Suara::with('kategori')->latest()->take(5)->get();

        return view('dashboard.pengelola', compact('hitung', 'tayang', 'mintaHapus', 'terbaru'));
    }

    public function index(Request $request)
    {
        $query = Suara::with(['kategori', 'user', 'riwayat'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }
        $tampilan = $request->input('tampilan');
        if ($tampilan === 'tayang') {
            $query->where('status', 'disetujui')->where('ingin_publikasi', true);
        } elseif ($tampilan === 'minta') {
            $query->where('ingin_publikasi', true);
        } elseif ($tampilan === 'rahasia') {
            $query->where('ingin_publikasi', false);
        } elseif ($tampilan === 'hapus') {
            $query->whereNotNull('hapus_diminta_at');
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('judul', 'like', "%{$q}%")->orWhere('nomor', 'like', "%{$q}%");
            });
        }

        $daftar   = $query->paginate(10)->withQueryString();
        $kategori = Kategori::orderBy('nama')->get();

        return view('pengelola.suara.index', compact('daftar', 'kategori'));
    }

    public function show(Suara $suara)
    {
        $suara->load(['kategori', 'user', 'lampiran', 'riwayat']);

        return view('pengelola.suara.show', compact('suara'));
    }

    // Satu keputusan untuk status sekaligus publikasi; bisa diubah kapan saja
    public function putuskan(Request $request, Suara $suara)
    {
        $data = $request->validate([
            'status'       => ['required', 'in:diajukan,disetujui,ditolak'],
            'mode_catatan' => ['nullable', 'in:tanpa,dengan'],
            'catatan'      => ['nullable', 'string', 'max:500'],
        ]);

        // Catatan hanya dipakai kalau Ditolak DAN pengelola memilih "dengan catatan"
        $catatan = null;
        if ($data['status'] === 'ditolak' && ($data['mode_catatan'] ?? 'tanpa') === 'dengan') {
            $catatan = trim($data['catatan'] ?? '') ?: null;
        }

        DB::transaction(function () use ($request, $suara, $data, $catatan) {
            $suara->update(['status' => $data['status']]);

            // Riwayat tidak berulang: entri keputusan lama dibuang, lalu dibuat satu entri baru
            SuaraRiwayat::where('suara_id', $suara->id)->whereIn('status', ['disetujui', 'ditolak'])->delete();

            if (in_array($data['status'], ['disetujui', 'ditolak'])) {
                $suara->riwayat()->create([
                    'status'          => $data['status'],
                    'diputuskan_oleh' => $request->user()->id,
                    'catatan'         => $catatan,
                ]);
            }

            // Permintaan hapus hanya berlaku untuk suara yang disetujui
            if ($data['status'] !== 'disetujui') {
                $suara->update(['hapus_diminta_at' => null, 'alasan_hapus' => null]);
            }
        });

        return back()->with('sukses', 'Keputusan disimpan.');
    }

    public function hapus(Suara $suara)
    {
        $suara->delete(); // soft delete

        return redirect()->route('pengelola.suara.index')->with('sukses', 'Suara dihapus.');
    }

    public function tanggapiHapus(Request $request, Suara $suara)
    {
        abort_if($suara->hapus_diminta_at === null, 422);

        $data = $request->validate([
            'keputusan' => ['required', 'in:setujui,tolak'],
        ]);

        if ($data['keputusan'] === 'setujui') {
            $suara->delete();

            return redirect()->route('pengelola.suara.index')
                ->with('sukses', 'Permintaan disetujui, suara dihapus.');
        }

        $suara->update(['hapus_diminta_at' => null, 'alasan_hapus' => null]);

        return back()->with('sukses', 'Permintaan penghapusan ditolak; suara tetap seperti semula.');
    }
}