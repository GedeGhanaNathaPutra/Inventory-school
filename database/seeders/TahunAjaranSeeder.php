<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    public function run(): void
    {
        TahunAjaran::firstOrCreate(
            ['nama_tahun_ajaran' => '2025/2026'],
            [
                'tanggal_mulai' => '2025-07-01',
                'tanggal_selesai' => '2026-06-30',
                'status' => 'aktif',
            ]
        );
    }
}
