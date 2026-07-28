<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'prodi_id', 'phone', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    public function barangs(): HasMany
    {
        return $this->hasMany(Barang::class, 'dicatat_oleh');
    }

    public function kondisiHistories(): HasMany
    {
        return $this->hasMany(KondisiHistory::class, 'dilaporkan_oleh');
    }

    public function kebutuhanRuangans(): HasMany
    {
        return $this->hasMany(KebutuhanRuangan::class, 'dicatat_oleh');
    }

    public function pengajuans(): HasMany
    {
        return $this->hasMany(Pengajuan::class, 'diajukan_oleh');
    }

    public function pengajuanLogs(): HasMany
    {
        return $this->hasMany(PengajuanLog::class, 'updated_by');
    }

    public function serahTerimaDari(): HasMany
    {
        return $this->hasMany(SerahTerima::class, 'dari_user_id');
    }

    public function serahTerimaKe(): HasMany
    {
        return $this->hasMany(SerahTerima::class, 'ke_user_id');
    }

    public function serahTerimaDibuat(): HasMany
    {
        return $this->hasMany(SerahTerima::class, 'dibuat_oleh');
    }
}
