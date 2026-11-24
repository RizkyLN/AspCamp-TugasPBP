@extends('layouts.utama')
@section('title', 'Suara Saya')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Suara Saya</h4>
  <a href="{{ route('suara.create') }}" class="btn btn-primary">+ Kirim Suara</a>
</div>

@if (session('sukses'))
  <div class="alert alert-success">{{ session('sukses') }}</div>
@endif

<p class="text-muted small">Suara yang kamu kirim secara anonim tidak ditampilkan di sini karena tidak terhubung ke akunmu.</p>

<div class="card shadow-sm"><div class="table-responsive">
  <table class="table table-hover mb-0 align-middle">
    <thead>
      <tr><th>Nomor</th><th>Judul</th><th>Jenis</th><th>Kategori</th><th>Status</th><th>Tayang</th><th>Dikirim</th><th></th></tr>
    </thead>
    <tbody>
      @forelse ($daftar as $s)
        <tr>
          <td class="small">{{ $s->nomor }}</td>
          <td>{{ $s->judul }}</td>
          <td><span class="badge text-bg-{{ $s->jenis_badge }}">{{ ucfirst($s->jenis) }}</span></td>
          <td>{{ $s->kategori->nama }}</td>
          <td><span class="badge text-bg-{{ $s->status_badge }}">{{ ucfirst($s->status) }}</span></td>
          <td class="small">
            @if ($s->hapus_diminta_at) Menunggu penghapusan
            @elseif ($s->tayang) Tayang di beranda
            @elseif ($s->ingin_publikasi) Belum tayang
            @else Rahasia
            @endif
          </td>
          <td>{{ $s->created_at->format('d M Y') }}</td>
          <td><a href="{{ route('suara.show', $s) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
        </tr>
      @empty
        <tr><td colspan="8" class="text-center text-muted py-4">Belum ada suara.</td></tr>
      @endforelse
    </tbody>
  </table>
</div></div>

<div class="mt-3">{{ $daftar->links() }}</div>
@endsection