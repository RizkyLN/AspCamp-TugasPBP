@props(['status'])

@switch($status)
    @case('dosen_staff')
        <i class="bi bi-person-badge-fill text-primary" title="Dosen / Staff"></i>
        @break
    @case('umum')
        <i class="bi bi-person-circle text-secondary" title="Umum"></i>
        @break
    @default
        <i class="bi bi-mortarboard-fill text-success" title="Mahasiswa"></i>
@endswitch