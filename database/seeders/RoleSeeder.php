<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    private array $roles = [
        [
            'role' => 'kepsek',
            'name' => 'Kepala Sekolah',
            'email' => 'kepsek@sekolah.test',
            'permissions' => [
                'view_all_data', 'approve_rapbs', 'approve_write_off',
                'manage_users', 'export_reports',
            ],
        ],
        [
            'role' => 'waka_sarpras',
            'name' => 'Waka Sarpras',
            'email' => 'waka@sekolah.test',
            'permissions' => [
                'view_all_data', 'submit_request', 'forward_to_rapbs',
                'update_dibelanjakan', 'create_handover', 'update_kondisi',
                'propose_write_off', 'export_reports',
            ],
        ],
        [
            'role' => 'ka_tu',
            'name' => 'Kepala TU',
            'email' => 'katu@sekolah.test',
            'permissions' => [
                'view_all_data', 'crud_barang', 'update_dibelanjakan',
                'record_handover', 'execute_write_off', 'manage_users',
                'export_reports',
            ],
        ],
        [
            'role' => 'ka_prodi',
            'name' => 'Kepala Prodi',
            'email' => 'kaprodi@sekolah.test',
            'permissions' => [
                'submit_request', 'acknowledge_handover',
                'update_kondisi_prodi', 'view_prodi_data',
            ],
        ],
    ];

    public function run(): void
    {
        foreach ($this->roles as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => bcrypt('password'),
                    'role' => $user['role'],
                    'is_active' => true,
                ]
            );
        }
    }
}
