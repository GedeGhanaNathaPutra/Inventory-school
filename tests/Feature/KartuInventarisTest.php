<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Prodi;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KartuInventarisTest extends TestCase
{
    use RefreshDatabase;

    private User $kaTu;
    private Ruangan $ruangan;

    protected function setUp(): void
    {
        parent::setUp();
        $prodi = Prodi::factory()->create();
        $this->ruangan = Ruangan::factory()->create(['prodi_id' => $prodi->id]);
        $this->kaTu = User::factory()->create(['role' => 'ka_tu']);
    }

    public function test_index_lists_ruangan(): void
    {
        $this->actingAs($this->kaTu)->get('/kartu')->assertOk()->assertSee($this->ruangan->nama_ruangan);
    }

    public function test_show_kartu(): void
    {
        Barang::factory()->create([
            'nama_barang' => 'Meja',
            'kondisi' => 'baik',
            'kuantitas' => 5,
            'ruangan_id' => $this->ruangan->id,
            'dicatat_oleh' => $this->kaTu->id,
        ]);

        $this->actingAs($this->kaTu);
        $this->get("/kartu/{$this->ruangan->id}")
            ->assertOk()
            ->assertSee('Meja')
            ->assertSee('5');
    }

    public function test_can_update_kebutuhan(): void
    {
        $this->actingAs($this->kaTu);

        $this->post("/kartu/{$this->ruangan->id}/kebutuhan", [
            'nama_barang' => 'Meja',
            'keterangan' => 'Beberapa meja mulai aus',
            'kebutuhan' => 'Butuh 5 meja baru',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('kebutuhan_ruangan', [
            'ruangan_id' => $this->ruangan->id,
            'nama_barang' => 'Meja',
            'kebutuhan' => 'Butuh 5 meja baru',
        ]);
    }
}
