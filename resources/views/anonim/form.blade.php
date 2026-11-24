@extends('layouts.utama')
@section('title', 'Suara Anonim')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-5">
    @if (session('sukses'))
      <div class="alert alert-success">{{ session('sukses') }}</div>
    @endif
    <div class="card shadow-sm"><div class="card-body">
      <h4 class="mb-1">Kelola Suara Anonim</h4>
      <p class="text-muted small">
        Masukkan nomor dan kode kelola yang kamu terima saat mengirim. Suara yang belum disetujui pengelola
        bisa diubah atau dibatalkan.
      </p>
      <form method="POST" action="{{ route('anonim.buka') }}">
        @csrf
        <div class="mb-3">
          <label class="form-label">Nomor</label>
          <input type="text" name="nomor" value="{{ old('nomor') }}" class="form-control @error('nomor') is-invalid @enderror" placeholder="SRA-20261116-XXXXX">
          @error('nomor') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
          <label class="form-label">Kode kelola</label>
          <input type="text" name="kode_kelola" class="form-control @error('kode_kelola') is-invalid @enderror" autocomplete="off">
          @error('kode_kelola') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button class="btn btn-primary w-100">Buka</button>
      </form>
    </div></div>
  </div>
</div>
@endsection