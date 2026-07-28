<?php

namespace Database\Factories;

use App\Models\SerahTerima;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SerahTerimaFactory extends Factory
{
    protected $model = SerahTerima::class;

    public function definition(): array
    {
        return [
            'nomor_berita_acara' => 'BA-2026-' . str_pad(fake()->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'dari_user_id' => User::factory(),
            'ke_user_id' => User::factory(),
            'tanggal_serah_terima' => now(),
            'status' => 'draft',
            'dibuat_oleh' => User::factory(),
        ];
    }
}
