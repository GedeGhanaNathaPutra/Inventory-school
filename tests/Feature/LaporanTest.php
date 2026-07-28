<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanTest extends TestCase
{
    use RefreshDatabase;

    private User $kepsek;

    protected function setUp(): void
    {
        parent::setUp();
        $this->kepsek = User::factory()->create(['role' => 'kepsek']);
    }

    public function test_menu_page_loads(): void
    {
        $this->actingAs($this->kepsek)->get('/laporan')->assertOk();
    }

    public function test_kategori_report(): void
    {
        $this->actingAs($this->kepsek)->get('/laporan/kategori')->assertOk();
    }

    public function test_kondisi_report(): void
    {
        $this->actingAs($this->kepsek)->get('/laporan/kondisi')->assertOk();
    }

    public function test_lokasi_report(): void
    {
        $this->actingAs($this->kepsek)->get('/laporan/lokasi')->assertOk();
    }

    public function test_pengadaan_report(): void
    {
        $this->actingAs($this->kepsek)->get('/laporan/pengadaan')->assertOk();
    }
}
