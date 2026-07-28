<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanItem extends Model
{
    protected $table = 'pengajuan_item';

    public $timestamps = false;

    protected $fillable = [
        'pengajuan_id', 'nama_barang', 'jumlah', 'satuan',
        'estimasi_harga', 'keterangan', 'barang_id',
    ];

    protected function casts(): array
    {
        return ['estimasi_harga' => 'decimal:2'];
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
