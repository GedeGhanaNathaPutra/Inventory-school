<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengajuan extends Model
{
    use HasFactory;
    protected $table = 'pengajuan';

    protected $fillable = [
        'kode_pengajuan', 'kategori', 'tahun_ajaran_id', 'sumber',
        'kebutuhan_ruangan_id', 'diajukan_oleh',
        'status', 'catatan',
    ];

    public function diajukanOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diajukan_oleh');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function kebutuhanRuangan(): BelongsTo
    {
        return $this->belongsTo(KebutuhanRuangan::class, 'kebutuhan_ruangan_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PengajuanItem::class, 'pengajuan_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(PengajuanLog::class, 'pengajuan_id');
    }
}
