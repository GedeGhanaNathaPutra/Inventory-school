<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahunAjaran extends Model
{
    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'nama_tahun_ajaran', 'tanggal_mulai', 'tanggal_selesai', 'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function barangs(): HasMany
    {
        return $this->hasMany(Barang::class, 'tahun_ajaran_id');
    }

    public function serahTerimas(): HasMany
    {
        return $this->hasMany(SerahTerima::class, 'tahun_ajaran_id');
    }

    public function pengajuans(): HasMany
    {
        return $this->hasMany(Pengajuan::class, 'tahun_ajaran_id');
    }

    public function kebutuhanRuangans(): HasMany
    {
        return $this->hasMany(KebutuhanRuangan::class, 'tahun_ajaran_id');
    }
}
