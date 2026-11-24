<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            'Akademik', 'Fasilitas & Sarana', 'Keuangan',
            'Kemahasiswaan', 'Layanan Administrasi', 'Lainnya',
        ];
        foreach ($kategori as $nama) {
            Kategori::firstOrCreate(['nama' => $nama]);
        }

        // Pengelola (pemoderasi): hanya bisa dibuat lewat seeder/admin, bukan lewat form daftar
        User::updateOrCreate(['username' => 'pengelola_kemahasiswaan'], [
            'name'        => 'Pengelola Kemahasiswaan',
            'email'       => 'pengelola@unisnu.ac.id',
            'password'    => Hash::make('pengelola12345'),
            'role'        => 'pengelola',
            'status_akun' => 'dosen_staff',
        ]);

        User::updateOrCreate(['username' => 'mahasiswa_contoh'], [
            'name'        => 'Mahasiswa Contoh',
            'email'       => 'mahasiswa@unisnu.ac.id',
            'password'    => Hash::make('mahasiswa12345'),
            'role'        => 'pengguna',
            'status_akun' => 'mahasiswa',
        ]);
    }
}