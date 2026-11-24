@php
    $riwayatTolak = $s->riwayat->firstWhere('status', 'ditolak');
    $catatanLama = $riwayatTolak ? $riwayatTolak->catatan : null;
@endphp

<form method="POST" action="{{ route('pengelola.suara.keputusan', $s) }}" class="keputusan-form">
  @csrf
  @method('PATCH')

  <div class="d-flex gap-1">
    <select name="status" class="form-select form-select-sm status-select">
      @foreach (['diajukan' => 'Diajukan', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak'] as $nilai => $label)
        <option value="{{ $nilai }}" {{ $s->status === $nilai ? 'selected' : '' }}>{{ $label }}</option>
      @endforeach
    </select>
    <button class="btn btn-sm btn-primary">Simpan</button>
  </div>

  {{-- Kolom catatan baru muncul setelah "Ditolak" dipilih; catatan boleh diisi atau tidak --}}
  <div class="blok-catatan mt-2 {{ $s->status === 'ditolak' ? '' : 'd-none' }}">
    <label class="form-check small">
      <input type="radio" name="mode_catatan" value="tanpa" class="form-check-input" {{ $catatanLama ? '' : 'checked' }}> Tanpa catatan
    </label>
    <label class="form-check small">
      <input type="radio" name="mode_catatan" value="dengan" class="form-check-input" {{ $catatanLama ? 'checked' : '' }}> Dengan catatan
    </label>
    <textarea name="catatan" rows="2" placeholder="Catatan untuk pengirim"
              class="form-control form-control-sm mt-1 isi-catatan {{ $catatanLama ? '' : 'd-none' }}">{{ $catatanLama }}</textarea>
  </div>
</form>