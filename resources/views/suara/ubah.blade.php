@extends('layouts.utama')
@section('title', 'Ubah Suara')
@section('content')
@php
    $ditolak = $suara->riwayat->firstWhere('status', 'ditolak');
@endphp

<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card shadow-sm"><div class="card-body">
      <h4 class="mb-1">Ubah Suara</h4>
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

      <form method="POST" action="{{ route('suara.update', $suara) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('suara._form')

        <button type="submit" class="btn btn-primary">Simpan perubahan</button>
        <a href="{{ route('suara.show', $suara) }}" class="btn btn-outline-secondary">Batal</a>
      </form>
    </div></div>
  </div>
</div>
@endsection
