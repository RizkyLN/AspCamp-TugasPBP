@extends('layouts.utama')
@section('title', 'Dashboard Pengelola')
@section('content')
<h4 class="mb-3">Dashboard Pengelola</h4>

<div class="row g-3 mb-4">
  @foreach ([
    ['label' => 'Diajukan',  'jumlah' => $hitung['diajukan'] ?? 0,  'warna' => 'secondary', 'url' => ['status' => 'diajukan']],
    ['label' => 'Disetujui', 'jumlah' => $hitung['disetujui'] ?? 0, 'warna' => 'success',   'url' => ['status' => 'disetujui']],
    ['label' => 'Ditolak',   'jumlah' => $hitung['ditolak'] ?? 0,   'warna' => 'danger',    'url' => ['status' => 'ditolak']],
    ['label' => 'Tayang',    'jumlah' => $tayang,                   'warna' => 'primary',   'url' => ['tampilan' => 'tayang']],
    ['label' => 'Minta hapus', 'jumlah' => $mintaHapus,             'warna' => 'warning',   'url' => ['tampilan' => 'hapus']],
  ] as $kartu)
    <div class="col-6 col-md">
      <a href="{{ route('pengelola.suara.index', $kartu['url']) }}" class="text-decoration-none">
        <div class="card text-bg-{{ $kartu['warna'] }} text-center shadow-sm"><div class="card-body py-3">
          <div class="fs-3 fw-bold">{{ $kartu['jumlah'] }}</div>
          <div class="small">{{ $kartu['label'] }}</div>
        </div></div>
      </a>
    </div>
  @endforeach
</div>

<div class="card shadow-sm"><div class="card-body">
  <h6>Suara terbaru</h6>
  <table class="table table-sm align-middle mb-0">
    <tbody>
      @forelse ($terbaru as $s)
        <tr>
          <td class="small">{{ $s->nomor }}</td>
          <td>{{ $s->judul }}</td>
          <td><span class="badge text-bg-{{ $s->jenis_badge }}">{{ ucfirst($s->jenis) }}</span></td>
          <td><span class="badge text-bg-{{ $s->status_badge }}">{{ ucfirst($s->status) }}</span></td>
          <td><a href="{{ route('pengelola.suara.show', $s) }}" class="btn btn-sm btn-outline-primary">Buka</a></td>
        </tr>
      @empty
        <tr><td class="text-muted text-center">Belum ada suara.</td></tr>
      @endforelse
    </tbody>
  </table>
</div></div>
@endsection