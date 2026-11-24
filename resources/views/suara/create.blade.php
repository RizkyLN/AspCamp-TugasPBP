@extends('layouts.utama')
@section('title', 'Kirim Suara')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card shadow-sm"><div class="card-body">
      <h4 class="mb-3">Kirim Suara</h4>

      <form method="POST" action="{{ route('suara.store') }}" enctype="multipart/form-data">
        @csrf

        @include('suara._form')

        <div class="form-check mb-3">
          <input type="checkbox" name="is_anonim" value="1" id="is_anonim" class="form-check-input" {{ old('is_anonim') ? 'checked' : '' }}>
          <label class="form-check-label" for="is_anonim">
            <strong>Kirim sebagai anonim.</strong>
            Identitasmu tidak disimpan sama sekali, bahkan pengelola tidak tahu.
            Kamu akan menerima <em>nomor</em> dan <em>kode kelola</em> yang hanya tampil sekali; keduanya diperlukan untuk
            mengubah atau membatalkan suara ini lewat halaman Suara Anonim selama belum disetujui. Kalau hilang, tidak bisa dipulihkan.
            Jangan unggah lampiran yang memuat nama atau lokasi yang bisa mengungkap dirimu.
          </label>
        </div>

        <button class="btn btn-primary">Kirim</button>
        <a href="{{ route('pengguna.dashboard') }}" class="btn btn-outline-secondary">Batal</a>
      </form>
    </div></div>
  </div>
</div>
@endsection