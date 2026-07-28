<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarangFactory extends Factory
{
    protected $model = Barang::class;

    public function definition(): array
    {
        return [
            'kode_barang' => 'BOS-2026-' . str_pad(fake()->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'tanggal_pembukuan' => now(),
            'nama_barang' => fake()->word(),
            'kuantitas' => fake()->numberBetween(1, 50),
            'nama_satuan' => 'unit',
            'kategori' => fake()->randomElement(['bos', 'komite']),
            'jenis_barang' => fake()->randomElement(['inventaris', 'non_inventaris']),
            'kondisi' => 'baik',
            'status' => 'aktif',
            'dicatat_oleh' => User::factory(),
        ];
    }
}
