<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ruangan extends Model
{
    protected $table = 'ruangan';

    protected $fillable = ['nama_ruangan', 'prodi_id'];

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    public function barangs(): HasMany
    {
        return $this->hasMany(Barang::class, 'ruangan_id');
    }

    public function kebutuhanRuangans(): HasMany
    {
        return $this->hasMany(KebutuhanRuangan::class, 'ruangan_id');
    }
}
