<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('name');
            $table->enum('role', ['pengguna', 'pengelola'])->default('pengguna')->after('email');   // hak akses
            $table->enum('status_akun', ['mahasiswa', 'dosen_staff', 'umum'])->default('mahasiswa')->after('role'); // label identitas
            $table->string('avatar')->nullable()->after('status_akun');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'role', 'status_akun', 'avatar']);
        });
    }
};