@php
    $s = $suara ?? null;

    $pilihanJenis = [
        'keluhan'    => ['Keluhan', 'Masalah yang perlu ditindaklanjuti.'],
        'aspirasi'   => ['Aspirasi', 'Usulan atau saran untuk kampus.'],
        'pengalaman' => ['Pengalaman', 'Cerita atau informasi bermanfaat untuk sesama.'],
    ];

    $jenisTerpilih = old('jenis', $s ? $s->jenis : 'keluhan');
    $kategoriTerpilih = (string) old('kategori_id', $s ? $s->kategori_id : '');
    $cekPublikasi = session()->hasOldInput()
        ? (bool) old('ingin_publikasi')
        : ($s ? (bool) $s->ingin_publikasi : false);
@endphp

<label class="form-label">Jenis</label>
<div class="row g-2 mb-1">
  @foreach ($pilihanJenis as $nilai => $info)
    <div class="col-md-4">
      <input type="radio" class="btn-check" name="jenis" id="jenis-{{ $nilai }}" value="{{ $nilai }}"
             {{ $jenisTerpilih === $nilai ? 'checked' : '' }}>
      <label class="btn btn-outline-primary w-100 text-start h-100" for="jenis-{{ $nilai }}">
        <strong>{{ $info[0] }}</strong><br><small class="fw-normal">{{ $info[1] }}</small>
      </label>
    </div>
  @endforeach
</div>
@error('jenis')
  <div class="text-danger small mb-2">{{ $message }}</div>
@enderror

<div class="mb-3 mt-3">
  <label class="form-label" for="kategori_id">Kategori</label>
  <select name="kategori_id" id="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
    <option value="">-- pilih kategori --</option>
    @foreach ($kategori as $k)
      <option value="{{ $k->id }}" {{ $kategoriTerpilih === (string) $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
    @endforeach
  </select>
  @error('kategori_id')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label class="form-label" for="judul">Judul</label>
  <input type="text" name="judul" id="judul" value="{{ old('judul', $s ? $s->judul : '') }}"
         class="form-control @error('judul') is-invalid @enderror">
  @error('judul')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label class="form-label" for="isi">Isi (minimal 20 karakter)</label>
  <textarea name="isi" id="isi" rows="5" class="form-control @error('isi') is-invalid @enderror">{{ old('isi', $s ? $s->isi : '') }}</textarea>
  @error('isi')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  @if ($s && $s->lampiran->isNotEmpty())
    <div class="small text-muted mb-1">Lampiran saat ini (centang untuk menghapus):</div>
    @foreach ($s->lampiran as $l)
      <div class="form-check">
        <input type="checkbox" name="hapus_lampiran[]" value="{{ $l->id }}" class="form-check-input" id="hapus-{{ $l->id }}">
        <label class="form-check-label" for="hapus-{{ $l->id }}">{{ $l->nama_asli }}</label>
      </div>
    @endforeach
  @endif

  <label class="form-label mt-2" for="lampiran">Tambah lampiran (opsional, total maks. 3 file JPG/PNG/PDF, 2 MB per file)</label>
  <input type="file" name="lampiran[]" id="lampiran" multiple class="form-control">
  @error('lampiran')
    <div class="text-danger small">{{ $message }}</div>
  @enderror
  @foreach ($errors->get('lampiran.*') as $pesanFile)
    <div class="text-danger small">{{ $pesanFile[0] }}</div>
  @endforeach
</div>

<div class="form-check mb-3">
  <input type="checkbox" name="ingin_publikasi" value="1" id="ingin_publikasi" class="form-check-input"
         {{ $cekPublikasi ? 'checked' : '' }}>
  <label class="form-check-label" for="ingin_publikasi">
    <strong>Tayangkan di beranda publik</strong> setelah disetujui pengelola.
    Kalau tidak dicentang, suara hanya dilihat pengelola (rahasia).
  </label>
</div>

@push('scripts')
<script>
  // Untuk jenis Pengalaman, opsi "tayangkan" otomatis dicentang (tetap bisa dilepas)
  document.querySelectorAll('input[name="jenis"]').forEach(function (el) {
    el.addEventListener('change', function () {
      if (this.value === 'pengalaman') {
        document.getElementById('ingin_publikasi').checked = true;
      }
    });
  });
</script>
@endpush
