<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Prodi;
use App\Models\Ruangan;
use App\Models\SerahTerima;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SerahTerimaTest extends TestCase
{
    use RefreshDatabase;

    private User $waka;
    private User $kaProdi;
    private Ruangan $ruangan;
    private Barang $barang;

    protected function setUp(): void
    {
        parent::setUp();

        $prodi = Prodi::factory()->create();
        $this->ruangan = Ruangan::factory()->create(['prodi_id' => $prodi->id]);
        $this->waka = User::factory()->create(['role' => 'waka_sarpras']);
        $this->kaProdi = User::factory()->create(['role' => 'ka_prodi', 'prodi_id' => $prodi->id]);
        $this->barang = Barang::factory()->create(['dicatat_oleh' => $this->waka->id, 'ruangan_id' => null]);
    }

    public function test_waka_can_create_serah_terima(): void
    {
        $this->actingAs($this->waka);

        $this->post('/serah-terima', [
            'ke_user_id' => $this->kaProdi->id,
            'ruangan_tujuan_id' => $this->ruangan->id,
            'tanggal_serah_terima' => '2026-02-01',
            'items' => [
                ['barang_id' => $this->barang->id, 'jumlah' => 1, 'kondisi_saat_serah_terima' => 'baik'],
            ],
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('serah_terima', ['status' => 'draft']);
    }

    public function test_nomor_ba_auto_generated(): void
    {
        $this->actingAs($this->waka);
        $this->post('/serah-terima', [
            'ke_user_id' => $this->kaProdi->id,
            'ruangan_tujuan_id' => $this->ruangan->id,
            'tanggal_serah_terima' => '2026-02-01',
            'items' => [
                ['barang_id' => $this->barang->id, 'jumlah' => 1, 'kondisi_saat_serah_terima' => 'baik'],
            ],
        ]);

        $st = SerahTerima::first();
        $this->assertNotNull($st);
        $this->assertStringStartsWith('BA-2026-', $st->nomor_berita_acara);
    }

    public function test_penerima_can_acknowledge(): void
    {
        $serahTerima = SerahTerima::factory()->create([
            'dari_user_id' => $this->waka->id,
            'ke_user_id' => $this->kaProdi->id,
            'status' => 'draft',
        ]);
        $serahTerima->items()->create([
            'barang_id' => $this->barang->id,
            'jumlah' => 1,
            'kondisi_saat_serah_terima' => 'baik',
        ]);

        $this->actingAs($this->kaProdi);
        $this->post("/serah-terima/{$serahTerima->id}/acknowledge");

        $this->assertDatabaseHas('serah_terima', ['id' => $serahTerima->id, 'status' => 'selesai']);
    }

    public function test_acknowledge_updates_lokasi_barang(): void
    {
        $serahTerima = SerahTerima::factory()->create([
            'dari_user_id' => $this->waka->id,
            'ke_user_id' => $this->kaProdi->id,
            'ruangan_tujuan_id' => $this->ruangan->id,
            'status' => 'draft',
        ]);
        $serahTerima->items()->create([
            'barang_id' => $this->barang->id,
            'jumlah' => 1,
            'kondisi_saat_serah_terima' => 'baik',
        ]);

        $this->actingAs($this->kaProdi);
        $this->post("/serah-terima/{$serahTerima->id}/acknowledge");

        $this->assertDatabaseHas('barang', ['id' => $this->barang->id, 'ruangan_id' => $this->ruangan->id]);
    }

    public function test_create_stok_mutasi_on_acknowledge(): void
    {
        $serahTerima = SerahTerima::factory()->create([
            'dari_user_id' => $this->waka->id,
            'ke_user_id' => $this->kaProdi->id,
            'status' => 'draft',
        ]);
        $serahTerima->items()->create([
            'barang_id' => $this->barang->id,
            'jumlah' => 1,
            'kondisi_saat_serah_terima' => 'baik',
        ]);

        $this->actingAs($this->kaProdi);
        $this->post("/serah-terima/{$serahTerima->id}/acknowledge");

        $this->assertDatabaseHas('stok_mutasi', [
            'barang_id' => $this->barang->id,
            'jenis' => 'keluar',
            'referensi_tipe' => 'serah_terima',
        ]);
    }
}
