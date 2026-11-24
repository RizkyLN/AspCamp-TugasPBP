@props(['user' => null, 'size' => 40])

@php
    $style = "width:{$size}px;height:{$size}px;flex-shrink:0;";
@endphp

@if ($user && $user->avatar)
    <img src="{{ asset('storage/' . $user->avatar) }}" alt="" class="rounded-circle object-fit-cover" style="{{ $style }}">
@elseif ($user)
    {{-- Belum punya foto: lingkaran berisi huruf pertama username --}}
    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold bg-primary" style="{{ $style }}">
        {{ strtoupper(substr($user->username, 0, 1)) }}
    </div>
@else
    {{-- Anonim: abu-abu polos dengan siluet putih, gaya WhatsApp --}}
    <div class="rounded-circle d-flex align-items-end justify-content-center overflow-hidden" style="{{ $style }}background:#dfe5e7;">
        <svg viewBox="0 0 24 24" width="{{ $size * 0.85 }}" height="{{ $size * 0.85 }}" fill="#fff">
            <path d="M12 12a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9zm0 2c-4.4 0-8 2.2-8 5v2h16v-2c0-2.8-3.6-5-8-5z"/>
        </svg>
    </div>
@endif