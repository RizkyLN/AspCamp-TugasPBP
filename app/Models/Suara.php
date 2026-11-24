<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property bool $tayang
 * @property bool $bisa_dikelola
 * @property string $label_tampilan
 * @property string $status_badge
 * @property string $jenis_badge
 * @method static Builder|Suara tayang()
 */
class Suara extends Model
{
    use SoftDeletes;

    protected $table = 'suara';

    protected $fillable = [
        'nomor', 'kode_kelola', 'user_id', 'kategori_id', 'jenis', 'judul', 'isi',
        'is_anonim', 'ingin_publikasi', 'status', 'hapus_diminta_at', 'alasan_hapus',
    ];

    protected $hidden = ['kode_kelola'];

    protected $casts = [
        'is_anonim'        => 'boolean',
        'ingin_publikasi'  => 'boolean',
        'hapus_diminta_at' => 'datetime',
    ];

    public function user()     { return $this->belongsTo(User::class); }
    public function kategori() { return $this->belongsTo(Kategori::class); }
    public function lampiran() { return $this->hasMany(SuaraLampiran::class); }
    public function riwayat()  { return $this->hasMany(SuaraRiwayat::class)->oldest(); }

    // Hanya yang boleh tampil di beranda publik: disetujui DAN pengirim memilih tayang
    public function scopeTayang(Builder $query): Builder
    {
        return $query->where('status', 'disetujui')->where('ingin_publikasi', true);
    }

    public function getTayangAttribute(): bool
    {
        return $this->status === 'disetujui' && $this->ingin_publikasi;
    }

    // Boleh diubah/dibatalkan langsung oleh pengirim selama belum disetujui
    public function getBisaDikelolaAttribute(): bool
    {
        return $this->status !== 'disetujui';
    }

    public function getLabelTampilanAttribute(): string
    {
        if ($this->tayang) {
            return 'Tayang di beranda';
        }
        if ($this->ingin_publikasi && $this->status === 'diajukan') {
            return 'Menunggu keputusan tayang';
        }
        if ($this->ingin_publikasi) {
            return 'Tidak tayang';
        }

        return 'Rahasia';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'disetujui' => 'success',
            'ditolak'   => 'danger',
            default     => 'secondary',
        };
    }

    public function getJenisBadgeAttribute(): string
    {
        return match ($this->jenis) {
            'keluhan'  => 'warning',
            'aspirasi' => 'info',
            default    => 'success',
        };
    }
}