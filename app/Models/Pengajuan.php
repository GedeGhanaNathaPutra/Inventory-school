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
        'kode_pengajuan', 'kategori', 'diajukan_oleh',
        'status', 'catatan',
    ];

    public function diajukanOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diajukan_oleh');
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
