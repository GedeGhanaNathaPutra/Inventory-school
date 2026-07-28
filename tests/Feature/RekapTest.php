<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RekapTest extends TestCase
{
    use RefreshDatabase;

    public function test_rekap_page_loads(): void
    {
        $user = User::factory()->create(['role' => 'kepsek']);
        $this->actingAs($user)->get('/rekap-3-pihak')->assertOk();
    }

    public function test_rekap_shows_stats(): void
    {
        $user = User::factory()->create(['role' => 'kepsek']);
        $this->actingAs($user)->get('/rekap-3-pihak')
            ->assertSee('Data Master')
            ->assertSee('Data Distribusi')
            ->assertSee('Data Pemakaian');
    }
}
