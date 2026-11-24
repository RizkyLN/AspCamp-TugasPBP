@extends('layouts.utama')
@section('title', 'Suara Anonim Terkirim')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card shadow-sm border-warning"><div class="card-body text-center">
      <h4>Suara anonim terkirim</h4>
      <p class="text-muted">Catat atau foto kedua kode ini sekarang.</p>
      <p class="mb-1 small text-muted">Nomor</p>
      <p class="fs-4 fw-bold">{{ $nomor }}</p>
      <p class="mb-1 small text-muted">Kode kelola</p>
      <p class="fs-3 fw-bold text-danger">{{ $kode }}</p>
      <div class="alert alert-warning small text-start">
        Kode kelola <strong>tidak akan ditampilkan lagi</strong> dan tidak bisa dipulihkan. Kamu membutuhkannya untuk
        mengubah atau membatalkan suara ini di halaman Suara Anonim, selama belum disetujui pengelola.
        Jangan refresh halaman ini, nanti suaranya terkirim dua kali.
      </div>
      <a href="{{ route('anonim.form') }}" class="btn btn-primary">Ke halaman Suara Anonim</a>
      <a href="{{ route('pengguna.dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
    </div></div>
  </div>
</div>
@endsection