<?php

namespace Tests\Feature;

use App\Models\Prodi;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_loads_for_ka_tu(): void
    {
        $user = User::factory()->create(['role' => 'ka_tu']);
        $this->actingAs($user)->get('/dashboard')->assertOk();
    }

    public function test_dashboard_loads_for_kepsek(): void
    {
        $user = User::factory()->create(['role' => 'kepsek']);
        $this->actingAs($user)->get('/dashboard')->assertOk();
    }

    public function test_dashboard_loads_for_waka(): void
    {
        $user = User::factory()->create(['role' => 'waka_sarpras']);
        $this->actingAs($user)->get('/dashboard')->assertOk();
    }

    public function test_dashboard_loads_for_ka_prodi(): void
    {
        $prodi = Prodi::factory()->create();
        $user = User::factory()->create(['role' => 'ka_prodi', 'prodi_id' => $prodi->id]);
        $this->actingAs($user)->get('/dashboard')->assertOk();
    }

    public function test_dashboard_shows_stats_for_ka_tu(): void
    {
        $user = User::factory()->create(['role' => 'ka_tu']);
        $this->actingAs($user)->get('/dashboard')
            ->assertSee('Total Barang')
            ->assertSee('Ruangan');
    }

    public function test_guest_redirected(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }
}
