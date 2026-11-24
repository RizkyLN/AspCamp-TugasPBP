@extends('layouts.utama')
@section('title', 'Ubah Suara Anonim')
@section('content')
@php
    $ditolak = $suara->riwayat->firstWhere('status', 'ditolak');
@endphp

<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card shadow-sm"><div class="card-body">
      <h4 class="mb-1">Ubah Suara Anonim</h4>
      <p class="text-muted small">
        {{ $suara->nomor }} &middot; Status:
        <span class="badge text-bg-{{ $suara->status_badge }}">{{ ucfirst($suara->status) }}</span>
      </p>

      @if ($errors->any())
        <div class="alert alert-danger small">Ada isian yang belum benar. Periksa kembali formulir di bawah.</div>
      @endif

      @if ($ditolak)
        <div class="alert alert-warning small">
          Suara ini ditolak pengelola.
          @if ($ditolak->catatan) Catatan: {{ $ditolak->catatan }} @endif
          Setelah kamu simpan perubahan, suara diajukan ulang.
        </div>
      @endif

      <form method="POST" action="{{ route('anonim.simpan') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="nomor" value="{{ $suara->nomor }}">
        <input type="hidden" name="kode_kelola" value="{{ $kode }}">

        @include('suara._form')

        <button class="btn btn-primary">Simpan perubahan</button>
        <a href="{{ route('anonim.form') }}" class="btn btn-outline-secondary">Tutup</a>
      </form>

      <hr>
      <form method="POST" action="{{ route('anonim.batal') }}" onsubmit="return confirm('Batalkan dan hapus suara anonim ini?')">
        @csrf @method('DELETE')
        <input type="hidden" name="nomor" value="{{ $suara->nomor }}">
        <input type="hidden" name="kode_kelola" value="{{ $kode }}">
        <button class="btn btn-outline-danger">Batalkan suara ini</button>
      </form>
    </div></div>
  </div>
</div>
@endsection