@extends('layouts.utama')
@section('title', 'Detail Suara')
@section('content')
<a href="{{ route('pengelola.suara.index') }}" class="small">&larr; Kembali ke daftar</a>

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

      <div class="d-flex align-items-center gap-2 my-2">
        @if ($suara->is_anonim)
          <x-avatar :size="36" /> <strong>Anonim</strong>
          <span class="small text-muted">(identitas tidak tersimpan)</span>
        @elseif ($suara->user)
          <x-avatar :user="$suara->user" :size="36" />
          <strong>{{ $suara->user->username }}</strong>
          <x-status-icon :status="$suara->user->status_akun" />
          <span class="small text-muted">{{ $suara->user->name }} &middot; {{ $suara->user->email }}</span>
        @else
          <span class="text-muted small">(akun pengirim sudah dihapus)</span>
        @endif
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

    <div class="card shadow-sm"><div class="card-body">
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
  </div>

  <div class="col-lg-4">
    <div class="card shadow-sm mb-3"><div class="card-body">
      <h6>Keputusan</h6>
      @include('pengelola.suara._keputusan', ['s' => $suara])
    </div></div>

    @if ($suara->hapus_diminta_at)
      <div class="card shadow-sm border-warning mb-3"><div class="card-body">
        <h6>Permintaan penghapusan</h6>
        <p class="small mb-1">Diminta {{ $suara->hapus_diminta_at->format('d M Y H:i') }}</p>
        <p class="small">Alasan: {{ $suara->alasan_hapus ?: '(tidak diisi)' }}</p>
        <form method="POST" action="{{ route('pengelola.suara.tanggapi-hapus', $suara) }}" class="d-flex gap-2">
          @csrf @method('PATCH')
          <button name="keputusan" value="setujui" class="btn btn-danger flex-fill"
                  onclick="return confirm('Setujui dan hapus suara ini?')">Setujui hapus</button>
          <button name="keputusan" value="tolak" class="btn btn-outline-secondary flex-fill">Tolak</button>
        </form>
      </div></div>
    @endif

    <div class="card shadow-sm"><div class="card-body">
      <h6>Hapus suara</h6>
      <form method="POST" action="{{ route('pengelola.suara.hapus', $suara) }}"
            onsubmit="return confirm('Hapus suara ini?')">
        @csrf @method('DELETE')
        <button class="btn btn-outline-danger w-100">Hapus</button>
      </form>
    </div></div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('change', function (e) {
    const form = e.target.closest('.keputusan-form');
    if (!form) return;

    const status  = form.querySelector('.status-select').value;
    const dengan  = form.querySelector('input[name="mode_catatan"][value="dengan"]').checked;

    form.querySelector('.blok-catatan').classList.toggle('d-none', status !== 'ditolak');
    form.querySelector('.isi-catatan').classList.toggle('d-none', !(status === 'ditolak' && dengan));
  });
</script>
@endpush