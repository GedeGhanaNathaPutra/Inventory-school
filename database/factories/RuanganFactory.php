<?php

namespace Database\Factories;

use App\Models\Ruangan;
use Illuminate\Database\Eloquent\Factories\Factory;

class RuanganFactory extends Factory
{
    protected $model = Ruangan::class;

    public function definition(): array
    {
        return [
            'nama_ruangan' => fake()->unique()->word() . ' Room',
            'prodi_id' => null,
        ];
    }
}
