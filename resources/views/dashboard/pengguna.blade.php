@extends('layouts.utama')
@section('title', 'Dashboard Pengguna')
@section('content')
<h4>Halo, {{ auth()->user()->name }}!</h4>
<p class="text-muted">Sampaikan keluhan, aspirasi, atau pengalamanmu.</p>
<a href="{{ route('suara.create') }}" class="btn btn-primary">Kirim Suara</a>
<a href="{{ route('suara.index') }}" class="btn btn-outline-primary">Suara Saya</a>
@endsection