<?php

namespace Database\Seeders;

use App\Models\Prodi;
use App\Models\Ruangan;
use Illuminate\Database\Seeder;

class ProdiRuanganSeeder extends Seeder
{
    public function run(): void
    {
        $prodi = Prodi::firstOrCreate(['nama_prodi' => 'Teknik Komputer Jaringan']);
        Ruangan::firstOrCreate(['nama_ruangan' => 'Lab Komputer 1', 'prodi_id' => $prodi->id]);
        Ruangan::firstOrCreate(['nama_ruangan' => 'Lab Komputer 2', 'prodi_id' => $prodi->id]);

        $prodi2 = Prodi::firstOrCreate(['nama_prodi' => 'Akuntansi']);
        Ruangan::firstOrCreate(['nama_ruangan' => 'Lab Akuntansi', 'prodi_id' => $prodi2->id]);

        Ruangan::firstOrCreate(['nama_ruangan' => 'Ruang TU', 'prodi_id' => null]);
        Ruangan::firstOrCreate(['nama_ruangan' => 'Gudang Umum', 'prodi_id' => null]);
    }
}
