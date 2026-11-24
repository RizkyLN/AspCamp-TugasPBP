@extends('layouts.utama')
@section('title', 'Profil')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-5">
    @if (session('sukses'))
      <div class="alert alert-success">{{ session('sukses') }}</div>
    @endif
    <div class="card shadow-sm"><div class="card-body text-center">
      <div class="d-flex justify-content-center mb-3">
        <x-avatar :user="auth()->user()" :size="96" />
      </div>
      <h5 class="mb-0">
        {{ auth()->user()->username }} <x-status-icon :status="auth()->user()->status_akun" />
      </h5>
      <p class="text-muted small">{{ auth()->user()->name }} &middot; {{ auth()->user()->email }}</p>

      <form method="POST" action="{{ route('profil.avatar') }}" enctype="multipart/form-data" class="text-start">
        @csrf
        <label class="form-label">Ganti foto profil (JPG/PNG, maks. 1 MB)</label>
        <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror">
        @error('avatar') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <button class="btn btn-primary w-100 mt-2">Unggah</button>
      </form>

      @if (auth()->user()->avatar)
        <form method="POST" action="{{ route('profil.avatar.hapus') }}" class="mt-2">
          @csrf @method('DELETE')
          <button class="btn btn-outline-danger w-100">Hapus foto</button>
        </form>
      @endif
    </div></div>
  </div>
</div>
@endsection