<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KebutuhanRuangan extends Model
{
    protected $table = 'kebutuhan_ruangan';

    protected $fillable = [
        'ruangan_id', 'nama_barang', 'keterangan',
        'kebutuhan', 'dicatat_oleh', 'tanggal',
    ];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }

    public function dicatatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }
}
