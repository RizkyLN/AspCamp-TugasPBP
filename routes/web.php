<?php

use App\Http\Controllers\AnonimController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengelolaSuaraController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\SuaraController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isPengelola()
            ? redirect()->route('pengelola.dashboard')
            : redirect()->route('pengguna.dashboard');
    }

    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::post('/profil/avatar', [ProfilController::class, 'updateAvatar'])->name('profil.avatar');
    Route::delete('/profil/avatar', [ProfilController::class, 'hapusAvatar'])->name('profil.avatar.hapus');
});

// Kelola suara anonim: tanpa login, memakai nomor + kode kelola
Route::get('/anonim', [AnonimController::class, 'form'])->name('anonim.form');
Route::get('/anonim/buka', fn () => redirect()->route('anonim.form'));
Route::middleware('throttle:20,1')->group(function () {
    Route::post('/anonim/buka', [AnonimController::class, 'buka'])->name('anonim.buka');
    Route::put('/anonim/simpan', [AnonimController::class, 'simpan'])->name('anonim.simpan');
    Route::delete('/anonim/batal', [AnonimController::class, 'batal'])->name('anonim.batal');
});

// Pengguna
Route::middleware(['auth', 'role:pengguna'])->group(function () {
    Route::get('/pengguna/dashboard', fn () => view('dashboard.pengguna'))->name('pengguna.dashboard');

    Route::get('/suara', [SuaraController::class, 'index'])->name('suara.index');
    Route::get('/suara/kirim', [SuaraController::class, 'create'])->name('suara.create');
    Route::post('/suara', [SuaraController::class, 'store'])->name('suara.store');
    Route::get('/suara/{suara}', [SuaraController::class, 'show'])->name('suara.show');
    Route::get('/suara/{suara}/ubah', [SuaraController::class, 'edit'])->name('suara.edit');
    Route::put('/suara/{suara}', [SuaraController::class, 'update'])->name('suara.update');
    Route::delete('/suara/{suara}', [SuaraController::class, 'batalkan'])->name('suara.batalkan');
    Route::post('/suara/{suara}/ajukan-hapus', [SuaraController::class, 'ajukanHapus'])->name('suara.ajukan-hapus');
});

// Pengelola
Route::middleware(['auth', 'role:pengelola'])->prefix('pengelola')->name('pengelola.')->group(function () {
    Route::get('/dashboard', [PengelolaSuaraController::class, 'dashboard'])->name('dashboard');
    Route::get('/suara', [PengelolaSuaraController::class, 'index'])->name('suara.index');
    Route::get('/suara/{suara}', [PengelolaSuaraController::class, 'show'])->name('suara.show');
    Route::patch('/suara/{suara}/keputusan', [PengelolaSuaraController::class, 'putuskan'])->name('suara.keputusan');
    Route::patch('/suara/{suara}/permintaan-hapus', [PengelolaSuaraController::class, 'tanggapiHapus'])->name('suara.tanggapi-hapus');
    Route::delete('/suara/{suara}', [PengelolaSuaraController::class, 'hapus'])->name('suara.hapus');
});