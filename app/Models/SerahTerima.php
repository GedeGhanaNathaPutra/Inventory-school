<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SerahTerima extends Model
{
    protected $table = 'serah_terima';

    protected $fillable = [
        'nomor_berita_acara', 'dari_user_id', 'ke_user_id',
        'ruangan_tujuan_id', 'tanggal_serah_terima', 'status', 'catatan', 'dibuat_oleh',
    ];

    protected function casts(): array
    {
        return ['tanggal_serah_terima' => 'date'];
    }

    public function dariUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dari_user_id');
    }

    public function keUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ke_user_id');
    }

    public function dibuatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function ruanganTujuan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_tujuan_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(SerahTerimaItem::class, 'serah_terima_id');
    }
}
