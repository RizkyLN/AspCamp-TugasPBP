<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuaraLampiran extends Model
{
    protected $table = 'suara_lampiran';

    protected $fillable = ['suara_id', 'path', 'nama_asli'];
}