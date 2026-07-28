<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Kepala Sekolah',
            'email' => 'kepsek@sekolah.test',
            'password' => bcrypt('password'),
            'role' => 'kepsek',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Waka Sarpras',
            'email' => 'waka@sekolah.test',
            'password' => bcrypt('password'),
            'role' => 'waka_sarpras',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Kepala TU',
            'email' => 'katu@sekolah.test',
            'password' => bcrypt('password'),
            'role' => 'ka_tu',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Kepala Prodi',
            'email' => 'kaprodi@sekolah.test',
            'password' => bcrypt('password'),
            'role' => 'ka_prodi',
            'is_active' => true,
        ]);
    }
}
