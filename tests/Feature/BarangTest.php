<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Prodi;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BarangTest extends TestCase
{
    use RefreshDatabase;

    private User $kaTu;
    private User $kaProdi;
    private Ruangan $ruangan;

    protected function setUp(): void
    {
        parent::setUp();

        $prodi = Prodi::factory()->create(['nama_prodi' => 'TKJ']);
        $this->ruangan = Ruangan::factory()->create(['nama_ruangan' => 'Lab 1', 'prodi_id' => $prodi->id]);

        $this->kaTu = User::factory()->create(['role' => 'ka_tu']);
        $this->kaProdi = User::factory()->create(['role' => 'ka_prodi', 'prodi_id' => $prodi->id]);
    }

    public function test_ka_tu_can_create_barang(): void
    {
        $this->actingAs($this->kaTu);

        $this->post('/barang', [
            'tanggal_pembukuan' => '2026-01-15',
            'nama_barang' => 'Meja Kantor',
            'kuantitas' => 10,
            'nama_satuan' => 'unit',
            'kategori' => 'bos',
            'jenis_barang' => 'inventaris',
            'kondisi' => 'baik',
            'ruangan_id' => $this->ruangan->id,
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('barang', ['nama_barang' => 'Meja Kantor']);
    }

    public function test_kode_barang_auto_generated(): void
    {
        $this->actingAs($this->kaTu);
        $this->post('/barang', [
            'tanggal_pembukuan' => '2026-01-15',
            'nama_barang' => 'Meja',
            'kuantitas' => 1,
            'nama_satuan' => 'unit',
            'kategori' => 'bos',
            'jenis_barang' => 'inventaris',
            'kondisi' => 'baik',
        ]);

        $barang = Barang::where('nama_barang', 'Meja')->first();
        $this->assertNotNull($barang);
        $this->assertStringStartsWith('BOS-2026-', $barang->kode_barang);
    }

    public function test_non_ka_tu_cannot_create_barang(): void
    {
        $this->actingAs($this->kaProdi);

        $this->post('/barang', [
            'tanggal_pembukuan' => '2026-01-15',
            'nama_barang' => 'Meja',
            'kuantitas' => 1,
            'nama_satuan' => 'unit',
            'kategori' => 'bos',
            'jenis_barang' => 'inventaris',
            'kondisi' => 'baik',
        ])->assertForbidden();
    }

    public function test_all_roles_can_view_barang_list(): void
    {
        Barang::factory()->count(3)->create(['dicatat_oleh' => $this->kaTu->id]);

        $this->actingAs($this->kaTu);
        $this->get('/barang')->assertOk();

        $this->actingAs(User::factory()->create(['role' => 'kepsek']));
        $this->get('/barang')->assertOk();
    }

    public function test_filter_by_kategori(): void
    {
        Barang::factory()->create(['kategori' => 'bos', 'dicatat_oleh' => $this->kaTu->id]);
        Barang::factory()->create(['kategori' => 'komite', 'dicatat_oleh' => $this->kaTu->id]);

        $this->actingAs($this->kaTu);
        $this->get('/barang?kategori=bos')->assertOk();
    }

    public function test_destroy_sets_status_to_dihapuskan(): void
    {
        $barang = Barang::factory()->create(['dicatat_oleh' => $this->kaTu->id]);

        $this->actingAs($this->kaTu);
        $this->delete("/barang/{$barang->id}");

        $this->assertDatabaseHas('barang', ['id' => $barang->id, 'status' => 'dihapuskan']);
    }
}
