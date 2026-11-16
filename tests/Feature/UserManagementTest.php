<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private function owner(): User
    {
        return User::where('email', 'owner@percetakan.com')->firstOrFail();
    }

    public function test_owner_can_access_user_management(): void
    {
        $this->actingAs($this->owner())->get('/user')->assertOk();
        $this->actingAs($this->owner())->get('/user/create')->assertOk();
    }

    public function test_non_owner_users_cannot_access_user_management(): void
    {
        foreach (['kasir@percetakan.com', 'operator@percetakan.com'] as $email) {
            $user = User::where('email', $email)->firstOrFail();

            $this->actingAs($user)->get('/user')->assertForbidden();
            $this->actingAs($user)->get('/user/create')->assertForbidden();
            $this->actingAs($user)->post('/user', [])->assertForbidden();
        }

        $noRole = User::create([
            'name' => 'Tanpa Role',
            'email' => 'tanpa.role@percetakan.com',
            'password' => 'password123',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($noRole)->get('/user')->assertForbidden();
        $this->actingAs($noRole)->get('/user/create')->assertForbidden();
    }

    public function test_owner_can_create_user_with_role(): void
    {
        $response = $this->actingAs($this->owner())->post('/user', [
            'name' => 'Kasir Baru',
            'email' => 'kasir.baru@percetakan.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'kasir',
        ]);

        $response->assertRedirect(route('user.index'));
        $response->assertSessionHas('success', 'User berhasil ditambahkan.');

        $user = User::where('email', 'kasir.baru@percetakan.com')->firstOrFail();

        $this->assertTrue($user->hasRole('kasir'));
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_role_is_required_and_must_exist(): void
    {
        $owner = $this->owner();

        $this->actingAs($owner)->post('/user', [
            'name' => 'Tanpa Role',
            'email' => 'tanpa.role@percetakan.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('role');

        $this->actingAs($owner)->post('/user', [
            'name' => 'Role Palsu',
            'email' => 'role.palsu@percetakan.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'superadmin',
        ])->assertSessionHasErrors('role');

        $this->assertDatabaseMissing('users', ['email' => 'tanpa.role@percetakan.com']);
        $this->assertDatabaseMissing('users', ['email' => 'role.palsu@percetakan.com']);
    }

    public function test_owner_can_change_another_users_role(): void
    {
        $kasir = User::where('email', 'kasir@percetakan.com')->firstOrFail();

        $this->actingAs($this->owner())->put(route('user.update', $kasir), [
            'name' => $kasir->name,
            'email' => $kasir->email,
            'role' => 'operator',
        ])->assertRedirect(route('user.index'));

        $this->assertTrue($kasir->fresh()->hasRole('operator'));
    }

    public function test_owner_cannot_change_own_role_or_delete_self(): void
    {
        $owner = $this->owner();

        $this->actingAs($owner)->put(route('user.update', $owner), [
            'name' => $owner->name,
            'email' => $owner->email,
            'role' => 'kasir',
        ])->assertRedirect(route('user.index'));

        $this->assertTrue($owner->fresh()->hasRole('owner'));

        $this->actingAs($owner)->delete(route('user.destroy', $owner))
            ->assertSessionHas('error', 'Anda tidak dapat menghapus akun sendiri.');

        $this->assertDatabaseHas('users', ['id' => $owner->id]);
    }

    public function test_owner_can_delete_another_user(): void
    {
        $target = User::create([
            'name' => 'User Dihapus',
            'email' => 'user.dihapus@percetakan.com',
            'password' => 'password123',
            'email_verified_at' => now(),
        ]);
        $target->assignRole('kasir');

        $this->actingAs($this->owner())->delete(route('user.destroy', $target))
            ->assertRedirect(route('user.index'))
            ->assertSessionHas('success', 'User berhasil dihapus.');

        $this->assertDatabaseMissing('users', ['id' => $target->id]);
    }
}
