<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Pengajuan;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengajuanTest extends TestCase
{
    use RefreshDatabase;

    private User $kaProdi;
    private User $waka;
    private User $kepsek;
    private User $kaTu;

    protected function setUp(): void
    {
        parent::setUp();
        $prodi = Prodi::factory()->create();
        $this->kaProdi = User::factory()->create(['role' => 'ka_prodi', 'prodi_id' => $prodi->id]);
        $this->waka = User::factory()->create(['role' => 'waka_sarpras']);
        $this->kepsek = User::factory()->create(['role' => 'kepsek']);
        $this->kaTu = User::factory()->create(['role' => 'ka_tu']);
    }

    public function test_ka_prodi_can_create_pengajuan(): void
    {
        $this->actingAs($this->kaProdi);

        $this->post('/pengajuan', [
            'kategori' => 'bos',
            'items' => [
                ['nama_barang' => 'Komputer', 'jumlah' => 2, 'satuan' => 'unit', 'estimasi_harga' => 10000000],
            ],
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('pengajuan', ['kategori' => 'bos', 'status' => 'diajukan']);
    }

    public function test_kode_pengajuan_auto_generated(): void
    {
        $this->actingAs($this->kaProdi);
        $this->post('/pengajuan', [
            'kategori' => 'bos',
            'items' => [['nama_barang' => 'Komputer', 'jumlah' => 1, 'satuan' => 'unit']],
        ]);

        $p = Pengajuan::first();
        $this->assertStringStartsWith('PJ-BOS-2026-', $p->kode_pengajuan);
    }

    public function test_waka_can_forward_to_rapbs(): void
    {
        $pengajuan = Pengajuan::factory()->create(['status' => 'diajukan', 'diajukan_oleh' => $this->kaProdi->id]);

        $this->actingAs($this->waka);
        $this->post("/pengajuan/{$pengajuan->id}/forward-to-rapbs");

        $this->assertDatabaseHas('pengajuan', ['id' => $pengajuan->id, 'status' => 'diteruskan_rapbs']);
    }

    public function test_kepsek_can_approve(): void
    {
        $pengajuan = Pengajuan::factory()->create(['status' => 'diteruskan_rapbs', 'diajukan_oleh' => $this->kaProdi->id]);

        $this->actingAs($this->kepsek);
        $this->post("/pengajuan/{$pengajuan->id}/approve");

        $this->assertDatabaseHas('pengajuan', ['id' => $pengajuan->id, 'status' => 'disetujui']);
    }

    public function test_kepsek_can_reject(): void
    {
        $pengajuan = Pengajuan::factory()->create(['status' => 'diteruskan_rapbs', 'diajukan_oleh' => $this->kaProdi->id]);

        $this->actingAs($this->kepsek);
        $this->post("/pengajuan/{$pengajuan->id}/reject");

        $this->assertDatabaseHas('pengajuan', ['id' => $pengajuan->id, 'status' => 'ditolak']);
    }

    public function test_wrong_role_cannot_approve(): void
    {
        $pengajuan = Pengajuan::factory()->create(['status' => 'diteruskan_rapbs', 'diajukan_oleh' => $this->kaProdi->id]);

        $this->actingAs($this->kaProdi);
        $this->post("/pengajuan/{$pengajuan->id}/approve")->assertForbidden();
    }

    public function test_full_flow_to_selesai(): void
    {
        $this->actingAs($this->kaProdi);
        $this->post('/pengajuan', [
            'kategori' => 'bos',
            'items' => [['nama_barang' => 'PC', 'jumlah' => 1, 'satuan' => 'unit']],
        ]);
        $pengajuan = Pengajuan::first();

        $this->actingAs($this->waka);
        $this->post("/pengajuan/{$pengajuan->id}/forward-to-rapbs");

        $this->actingAs($this->kepsek);
        $this->post("/pengajuan/{$pengajuan->id}/approve");

        $this->actingAs($this->waka);
        $this->post("/pengajuan/{$pengajuan->id}/mark-dibelanjakan");
        $this->post("/pengajuan/{$pengajuan->id}/mark-diserahkan-waka");
        $this->post("/pengajuan/{$pengajuan->id}/mark-diserahkan-pengguna");

        $this->actingAs($this->kaTu);
        $this->post("/pengajuan/{$pengajuan->id}/mark-didata");

        $this->assertDatabaseHas('pengajuan', ['id' => $pengajuan->id, 'status' => 'selesai']);
        $this->assertDatabaseHas('barang', ['nama_barang' => 'PC']);
    }

    public function test_status_log_created(): void
    {
        $pengajuan = Pengajuan::factory()->create(['status' => 'diajukan', 'diajukan_oleh' => $this->kaProdi->id]);

        $this->actingAs($this->waka);
        $this->post("/pengajuan/{$pengajuan->id}/forward-to-rapbs");

        $this->assertDatabaseHas('pengajuan_log', [
            'pengajuan_id' => $pengajuan->id,
            'status' => 'diteruskan_rapbs',
        ]);
    }
}
