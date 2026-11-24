@extends('layouts.utama')
@section('title', 'Kelola Suara')
@section('content')
<h4 class="mb-3">Kelola Suara</h4>

@if (session('sukses'))
  <div class="alert alert-success">{{ session('sukses') }}</div>
@endif

<form method="GET" action="{{ route('pengelola.suara.index') }}" class="card shadow-sm mb-3">
  <div class="card-body">
    <div class="row g-2">
      <div class="col-md-3">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Cari judul / nomor">
      </div>
      <div class="col-md-2">
        <select name="status" class="form-select form-select-sm">
          <option value="">Semua status</option>
          @foreach (['diajukan' => 'Diajukan', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak'] as $nilai => $label)
            <option value="{{ $nilai }}" {{ request('status') === $nilai ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <select name="jenis" class="form-select form-select-sm">
          <option value="">Semua jenis</option>
          @foreach (['keluhan' => 'Keluhan', 'aspirasi' => 'Aspirasi', 'pengalaman' => 'Pengalaman'] as $nilai => $label)
            <option value="{{ $nilai }}" {{ request('jenis') === $nilai ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <select name="kategori_id" class="form-select form-select-sm">
          <option value="">Semua kategori</option>
          @foreach ($kategori as $k)
            <option value="{{ $k->id }}" {{ (string) request('kategori_id') === (string) $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <select name="tampilan" class="form-select form-select-sm">
          <option value="">Semua tampilan</option>
          @foreach (['tayang' => 'Tayang', 'minta' => 'Minta tayang', 'rahasia' => 'Rahasia', 'hapus' => 'Minta hapus'] as $nilai => $label)
            <option value="{{ $nilai }}" {{ request('tampilan') === $nilai ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-1 d-grid">
        <button type="submit" class="btn btn-sm btn-primary">Cari</button>
      </div>
    </div>
  </div>
</form>

<div class="card shadow-sm"><div class="table-responsive">
  <table class="table table-hover mb-0 align-middle">
    <thead>
      <tr>
        <th>Nomor</th><th>Judul</th><th>Pengirim</th><th>Jenis</th><th>Kategori</th>
        <th>Status</th><th>Tampilan</th><th>Dikirim</th><th></th>
      </tr>
    </thead>
    <tbody>
      @forelse ($daftar as $s)
        <tr>
          <td class="small">{{ $s->nomor }}</td>
          <td>
            {{ $s->judul }}
            @if ($s->hapus_diminta_at)
              <span class="badge text-bg-warning">Minta hapus</span>
            @endif
          </td>
          <td class="small">
            @if ($s->is_anonim)
              Anonim
            @elseif ($s->user)
              {{ $s->user->username }}
            @else
              <span class="text-muted">(akun dihapus)</span>
            @endif
          </td>
          <td><span class="badge text-bg-{{ $s->jenis_badge }}">{{ ucfirst($s->jenis) }}</span></td>
          <td>{{ $s->kategori ? $s->kategori->nama : '-' }}</td>
          <td><span class="badge text-bg-{{ $s->status_badge }}">{{ ucfirst($s->status) }}</span></td>
          <td class="small">{{ $s->label_tampilan }}</td>
          <td class="small">{{ $s->created_at->format('d M Y') }}</td>
          <td><a href="{{ route('pengelola.suara.show', $s) }}" class="btn btn-sm btn-outline-primary">Buka</a></td>
        </tr>
      @empty
        <tr><td colspan="9" class="text-center text-muted py-4">Tidak ada suara yang cocok.</td></tr>
      @endforelse
    </tbody>
  </table>
</div></div>

<div class="mt-3">{{ $daftar->links() }}</div>
@endsection
