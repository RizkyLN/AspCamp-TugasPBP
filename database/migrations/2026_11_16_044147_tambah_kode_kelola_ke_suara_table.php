<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suara', function (Blueprint $table) {
            $table->string('kode_kelola')->nullable()->after('nomor'); // hash, khusus suara anonim
        });
    }

    public function down(): void
    {
        Schema::table('suara', function (Blueprint $table) {
            $table->dropColumn('kode_kelola');
        });
    }
};