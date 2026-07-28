<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Prodi;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KondisiTest extends TestCase
{
    use RefreshDatabase;

    private User $kaTu;
    private Barang $barang;

    protected function setUp(): void
    {
        parent::setUp();
        $prodi = Prodi::factory()->create();
        $ruangan = Ruangan::factory()->create(['prodi_id' => $prodi->id]);
        $this->kaTu = User::factory()->create(['role' => 'ka_tu']);

        $this->barang = Barang::factory()->create([
            'kondisi' => 'baik',
            'ruangan_id' => $ruangan->id,
            'dicatat_oleh' => $this->kaTu->id,
        ]);
    }

    public function test_can_report_kondisi_with_photos(): void
    {
        $this->actingAs($this->kaTu);

        $this->post("/kondisi/{$this->barang->id}", [
            'kondisi' => 'rusak_ringan',
            'tanggal_lapor' => '2026-03-01',
            'foto_1' => UploadedFile::fake()->image('f1.jpg'),
            'foto_2' => UploadedFile::fake()->image('f2.jpg'),
            'foto_3' => UploadedFile::fake()->image('f3.jpg'),
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('kondisi_history', [
            'barang_id' => $this->barang->id,
            'kondisi' => 'rusak_ringan',
        ]);
    }

    public function test_rejects_without_photos_when_not_baik(): void
    {
        $this->actingAs($this->kaTu);

        // Ensure foto validation can trigger
        $response = $this->post("/kondisi/{$this->barang->id}", [
            'kondisi' => 'rusak_berat',
            'tanggal_lapor' => '2026-03-01',
        ]);

        // foto_1, foto_2, foto_3 should have validation errors
        $response->assertSessionHasErrors(['foto_1', 'foto_2', 'foto_3']);
    }

    public function test_allows_without_photos_when_kondisi_baik(): void
    {
        $this->actingAs($this->kaTu);

        $this->post("/kondisi/{$this->barang->id}", [
            'kondisi' => 'baik',
            'tanggal_lapor' => '2026-03-01',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('kondisi_history', [
            'barang_id' => $this->barang->id,
            'kondisi' => 'baik',
        ]);
    }

    public function test_updates_barang_kondisi_snapshot(): void
    {
        $this->actingAs($this->kaTu);

        $this->post("/kondisi/{$this->barang->id}", [
            'kondisi' => 'rusak_sedang',
            'tanggal_lapor' => '2026-03-01',
            'foto_1' => UploadedFile::fake()->image('f1.jpg'),
            'foto_2' => UploadedFile::fake()->image('f2.jpg'),
            'foto_3' => UploadedFile::fake()->image('f3.jpg'),
        ]);

        $this->assertDatabaseHas('barang', ['id' => $this->barang->id, 'kondisi' => 'rusak_sedang']);
    }

    public function test_history_page_shows_timeline(): void
    {
        $this->actingAs($this->kaTu);
        $this->get("/kondisi/{$this->barang->id}/history")->assertOk();
    }
}
