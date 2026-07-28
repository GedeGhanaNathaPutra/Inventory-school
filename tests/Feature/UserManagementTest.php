<?php

namespace Tests\Feature;

use App\Models\Prodi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $kepsek;

    protected function setUp(): void
    {
        parent::setUp();
        $this->kepsek = User::factory()->create(['role' => 'kepsek']);
    }

    public function test_kepsek_can_create_user(): void
    {
        $this->actingAs($this->kepsek);

        $this->post('/user', [
            'name' => 'New User',
            'email' => 'new@test.test',
            'password' => 'secret123',
            'role' => 'ka_tu',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', ['email' => 'new@test.test', 'role' => 'ka_tu']);
    }

    public function test_ka_prodi_wajib_punya_prodi(): void
    {
        $this->actingAs($this->kepsek);

        $response = $this->post('/user', [
            'name' => 'New Prodi',
            'email' => 'prodi@test.test',
            'password' => 'secret123',
            'role' => 'ka_prodi',
        ]);

        $response->assertSessionHasErrors(['prodi_id']);
    }

    public function test_ka_tu_can_access_user_management(): void
    {
        $kaTu = User::factory()->create(['role' => 'ka_tu']);
        $this->actingAs($kaTu)->get('/user')->assertOk();
    }

    public function test_ka_prodi_cannot_access_user_management(): void
    {
        $kaProdi = User::factory()->create(['role' => 'ka_prodi']);
        $this->actingAs($kaProdi)->get('/user')->assertForbidden();
    }

    public function test_can_toggle_active(): void
    {
        $target = User::factory()->create(['role' => 'ka_tu', 'is_active' => true]);

        $this->actingAs($this->kepsek);
        $this->post("/user/{$target->id}/toggle-active");

        $this->assertDatabaseHas('users', ['id' => $target->id, 'is_active' => false]);
    }

    public function test_can_edit_user(): void
    {
        $target = User::factory()->create(['role' => 'ka_tu']);

        $this->actingAs($this->kepsek);
        $response = $this->put("/user/{$target->id}", [
            'name' => 'Updated Name',
            'email' => $target->email,
            'role' => 'ka_tu',
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $target->refresh();
        $this->assertEquals('Updated Name', $target->name);
    }
}
