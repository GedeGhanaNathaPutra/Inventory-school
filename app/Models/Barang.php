<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    protected $table = 'barang';

    protected $fillable = [
        'kode_barang', 'tanggal_pembukuan', 'nama_barang',
        'keterangan_nomor_ukuran', 'merek_type', 'kuantitas',
        'nama_satuan', 'kategori', 'jenis_barang',
        'kelengkapan_dokumen', 'kondisi', 'harga', 'keterangan',
        'ruangan_id', 'status', 'dicatat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pembukuan' => 'date',
            'harga' => 'decimal:2',
        ];
    }

    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }

    public function dicatatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }

    public function kondisiHistories(): HasMany
    {
        return $this->hasMany(KondisiHistory::class, 'barang_id');
    }

    public function serahTerimaItems(): HasMany
    {
        return $this->hasMany(SerahTerimaItem::class, 'barang_id');
    }

    public function pengajuanItems(): HasMany
    {
        return $this->hasMany(PengajuanItem::class, 'barang_id');
    }

    public function stokMutasis(): HasMany
    {
        return $this->hasMany(StokMutasi::class, 'barang_id');
    }
}
