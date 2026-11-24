<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuaraRiwayat extends Model
{
    protected $table = 'suara_riwayat';

    protected $fillable = ['suara_id', 'diputuskan_oleh', 'status', 'catatan'];
}