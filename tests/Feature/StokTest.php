<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StokTest extends TestCase
{
    use RefreshDatabase;

    private User $kaTu;
    private Barang $barang;

    protected function setUp(): void
    {
        parent::setUp();
        $this->kaTu = User::factory()->create(['role' => 'ka_tu']);
        $this->barang = Barang::factory()->create(['dicatat_oleh' => $this->kaTu->id]);
    }

    public function test_index_shows_stok(): void
    {
        $this->actingAs($this->kaTu)->get('/stok')->assertOk();
    }

    public function test_can_create_mutasi_masuk(): void
    {
        $this->actingAs($this->kaTu);

        $this->post('/stok', [
            'barang_id' => $this->barang->id,
            'jenis' => 'masuk',
            'jumlah' => 5,
            'tanggal' => '2026-04-01',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('stok_mutasi', [
            'barang_id' => $this->barang->id,
            'jenis' => 'masuk',
            'jumlah' => 5,
        ]);
    }

    public function test_can_create_mutasi_keluar(): void
    {
        $this->actingAs($this->kaTu);

        $this->post('/stok', [
            'barang_id' => $this->barang->id,
            'jenis' => 'keluar',
            'jumlah' => 3,
            'tanggal' => '2026-04-01',
        ])->assertSessionHasNoErrors();
    }

    public function test_show_mutasi_history(): void
    {
        $this->actingAs($this->kaTu);
        $this->get("/stok/{$this->barang->id}")->assertOk();
    }
}
