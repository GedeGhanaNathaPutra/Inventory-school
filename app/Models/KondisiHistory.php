<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KondisiHistory extends Model
{
    protected $table = 'kondisi_history';

    protected $fillable = [
        'barang_id', 'kondisi', 'keterangan',
        'foto_1', 'foto_2', 'foto_3',
        'dilaporkan_oleh', 'tanggal_lapor',
    ];

    protected function casts(): array
    {
        return ['tanggal_lapor' => 'date'];
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function dilaporkanOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dilaporkan_oleh');
    }
}
