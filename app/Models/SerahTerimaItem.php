<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SerahTerimaItem extends Model
{
    protected $table = 'serah_terima_item';

    public $timestamps = false;

    protected $fillable = [
        'serah_terima_id', 'barang_id', 'jumlah',
        'kondisi_saat_serah_terima',
    ];

    public function serahTerima(): BelongsTo
    {
        return $this->belongsTo(SerahTerima::class, 'serah_terima_id');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
