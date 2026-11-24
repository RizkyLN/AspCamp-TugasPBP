<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        Schema::create('suara', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();   // nomor referensi, mis. SRA-20261111-AB12C
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // NULL = anonim
            $table->foreignId('kategori_id')->constrained('kategori');
            $table->enum('jenis', ['keluhan', 'aspirasi', 'pengalaman']);
            $table->string('judul');
            $table->text('isi');
            $table->boolean('is_anonim')->default(false);
            $table->boolean('ingin_publikasi')->default(false);   // pilihan pengirim
            $table->enum('status', ['diajukan', 'disetujui', 'ditolak'])->default('diajukan');
            $table->timestamp('hapus_diminta_at')->nullable();    // pengirim meminta penghapusan
            $table->text('alasan_hapus')->nullable();
            $table->timestamps();
            $table->softDeletes();   // kolom deleted_at untuk soft delete
        });

        Schema::create('suara_lampiran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('suara_id')->constrained('suara')->cascadeOnDelete();
            $table->string('path');
            $table->string('nama_asli');
            $table->timestamps();
        });

        Schema::create('suara_riwayat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('suara_id')->constrained('suara')->cascadeOnDelete();
            $table->foreignId('diputuskan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['diajukan', 'disetujui', 'ditolak']);
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Satu suara hanya boleh punya satu entri per status, jadi riwayat tidak berulang
            $table->unique(['suara_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suara_riwayat');
        Schema::dropIfExists('suara_lampiran');
        Schema::dropIfExists('suara');
        Schema::dropIfExists('kategori');
    }
};