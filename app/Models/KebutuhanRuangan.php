<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KebutuhanRuangan extends Model
{
    protected $table = 'kebutuhan_ruangan';

    protected $fillable = [
        'ruangan_id', 'tahun_ajaran_id', 'nama_barang',
        'jumlah_dibutuhkan', 'keterangan', 'kebutuhan',
        'status', 'pengajuan_id', 'dicatat_oleh', 'tanggal',
    ];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function dicatatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }
}
