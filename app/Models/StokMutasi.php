<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokMutasi extends Model
{
    protected $table = 'stok_mutasi';

    public $timestamps = false;

    protected $fillable = [
        'barang_id', 'jenis', 'jumlah', 'referensi_tipe',
        'referensi_id', 'tanggal', 'keterangan',
    ];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
