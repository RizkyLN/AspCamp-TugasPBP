@extends('layouts.utama')
@section('title', $suara->judul)
@section('content')
<a href="{{ route('suara.index') }}" class="small">&larr; Kembali ke Suara Saya</a>

@if (session('sukses'))
  <div class="alert alert-success mt-2">{{ session('sukses') }}</div>
@endif
@if ($errors->any())
  <div class="alert alert-danger mt-2">{{ $errors->first() }}</div>
@endif

<div class="row mt-2">
  <div class="col-lg-8">
    <div class="card shadow-sm mb-3"><div class="card-body">
      <div class="d-flex justify-content-between">
        <div>
          <div class="text-muted small">{{ $suara->nomor }} &middot; {{ $suara->kategori->nama }}</div>
          <h4 class="mt-1">{{ $suara->judul }}</h4>
        </div>
        <div class="text-end">
          <span class="badge text-bg-{{ $suara->jenis_badge }}">{{ ucfirst($suara->jenis) }}</span>
          <span class="badge text-bg-{{ $suara->status_badge }}">{{ ucfirst($suara->status) }}</span>
        </div>
      </div>
      <hr>
      <p style="white-space: pre-line">{{ $suara->isi }}</p>

      @if ($suara->lampiran->isNotEmpty())
        <h6>Lampiran</h6>
        <ul>
          @foreach ($suara->lampiran as $l)
            <li><a href="{{ asset('storage/' . $l->path) }}" target="_blank">{{ $l->nama_asli }}</a></li>
          @endforeach
        </ul>
      @endif

      <p class="small text-muted mb-0">Tampilan: <strong>{{ $suara->label_tampilan }}</strong></p>
    </div></div>
  </div>

  <div class="col-lg-4">
    <div class="card shadow-sm mb-3"><div class="card-body">
      <h6>Riwayat</h6>
      <ul class="list-group list-group-flush">
        @foreach ($suara->riwayat as $r)
          <li class="list-group-item px-0">
            <strong>{{ ucfirst($r->status) }}</strong>
            <span class="text-muted small">&middot; {{ $r->updated_at->format('d M Y H:i') }}</span>
            @if ($r->catatan) <div class="small">{{ $r->catatan }}</div> @endif
          </li>
        @endforeach
      </ul>
    </div></div>

    <div class="card shadow-sm"><div class="card-body">
      @if ($suara->bisa_dikelola)
        <h6>Kelola</h6>
        <a href="{{ route('suara.edit', $suara) }}" class="btn btn-primary w-100 mb-2">Ubah suara</a>
        <form method="POST" action="{{ route('suara.batalkan', $suara) }}"
              onsubmit="return confirm('Batalkan dan hapus pengajuan ini?')">
          @csrf @method('DELETE')
          <button class="btn btn-outline-danger w-100">Batalkan pengajuan</button>
        </form>
      @else
        <h6>Ajukan penghapusan</h6>
        <p class="small text-muted">Suara yang sudah disetujui tidak bisa diubah atau dibatalkan langsung.</p>
        @if ($suara->hapus_diminta_at)
          <div class="alert alert-warning small mb-0">
            Permintaan penghapusan dikirim {{ $suara->hapus_diminta_at->format('d M Y H:i') }} dan menunggu pengelola.
          </div>
        @else
          <form method="POST" action="{{ route('suara.ajukan-hapus', $suara) }}">
            @csrf
            <textarea name="alasan_hapus" rows="2" class="form-control mb-2" placeholder="Alasan (opsional)"></textarea>
            <button class="btn btn-outline-danger w-100">Ajukan penghapusan</button>
          </form>
        @endif
      @endif
    </div></div>
  </div>
</div>
@endsection