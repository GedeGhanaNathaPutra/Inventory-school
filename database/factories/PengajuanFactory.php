<?php

namespace Database\Factories;

use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PengajuanFactory extends Factory
{
    protected $model = Pengajuan::class;

    public function definition(): array
    {
        return [
            'kode_pengajuan' => 'PJ-BOS-2026-' . str_pad(fake()->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'kategori' => 'bos',
            'diajukan_oleh' => User::factory(),
            'status' => 'diajukan',
        ];
    }
}
