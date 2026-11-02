<?php

namespace Tests\Feature;

use App\Models\CustomRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_admin_can_view_monitoring_pages(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertStatus(200);
        $this->actingAs($admin)->get(route('admin.users.index'))->assertStatus(200)->assertSee('Mode Pantau');
        $this->actingAs($admin)->get(route('admin.roles.index'))->assertStatus(200)->assertSee('Mode Pantau');
    }

    public function test_admin_cannot_mutate_roles_or_system_roles(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $targetUser = User::factory()->create(['role' => 'user']);
        $customRole = CustomRole::create([
            'name'  => 'Kontributor',
            'color' => '#10b981',
        ]);

        // Admin cannot change system role
        $this->actingAs($admin)
            ->put(route('admin.users.system-role.update', $targetUser), [
                'role' => 'admin',
            ])
            ->assertStatus(403);

        // Admin cannot assign custom role
        $this->actingAs($admin)
            ->put(route('admin.users.roles.update', $targetUser), [
                'roles' => [$customRole->id],
            ])
            ->assertStatus(403);

        // Admin cannot create custom role
        $this->actingAs($admin)
            ->post(route('admin.roles.store'), [
                'name'  => 'Baru',
                'color' => '#e11d48',
            ])
            ->assertStatus(403);

        // Admin cannot update custom role
        $this->actingAs($admin)
            ->put(route('admin.roles.update', $customRole), [
                'name'  => 'Updated',
                'color' => '#3b82f6',
            ])
            ->assertStatus(403);

        // Admin cannot delete custom role
        $this->actingAs($admin)
            ->delete(route('admin.roles.destroy', $customRole))
            ->assertStatus(403);
    }

    public function test_owner_can_give_and_revoke_admin_role(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $targetUser = User::factory()->create(['role' => 'user']);

        // Give admin
        $response = $this->actingAs($owner)
            ->put(route('admin.users.system-role.update', $targetUser), [
                'role' => 'admin',
            ]);

        $response->assertSessionHas('success');
        $this->assertEquals('admin', $targetUser->fresh()->role);

        // Revoke admin (demote to user)
        $response = $this->actingAs($owner)
            ->put(route('admin.users.system-role.update', $targetUser), [
                'role' => 'user',
            ]);

        $response->assertSessionHas('success');
        $this->assertEquals('user', $targetUser->fresh()->role);
    }

    public function test_owner_can_promote_user_to_owner(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $targetUser = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($owner)
            ->put(route('admin.users.system-role.update', $targetUser), [
                'role' => 'owner',
            ]);

        $response->assertSessionHas('success');
        $this->assertEquals('owner', $targetUser->fresh()->role);
    }

    public function test_sole_owner_cannot_revoke_own_owner_role(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);

        $response = $this->actingAs($owner)
            ->put(route('admin.users.system-role.update', $owner), [
                'role' => 'admin',
            ]);

        $response->assertSessionHasErrors('role');
        $this->assertEquals('owner', $owner->fresh()->role);
    }

    public function test_owner_can_manage_custom_roles(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $targetUser = User::factory()->create(['role' => 'user']);

        // Create custom role
        $this->actingAs($owner)
            ->post(route('admin.roles.store'), [
                'name'  => 'Fotografer',
                'icon'  => '📸',
                'color' => '#3b82f6',
            ])
            ->assertSessionHas('success');

        $role = CustomRole::where('name', 'Fotografer')->firstOrFail();

        // Assign to user
        $this->actingAs($owner)
            ->put(route('admin.users.roles.update', $targetUser), [
                'roles' => [$role->id],
            ])
            ->assertSessionHas('success');

        $this->assertTrue($targetUser->fresh()->customRoles->contains($role));

        // Revoke role (empty array)
        $this->actingAs($owner)
            ->put(route('admin.users.roles.update', $targetUser), [
                'roles' => [],
            ])
            ->assertSessionHas('success');

        $this->assertFalse($targetUser->fresh()->customRoles->contains($role));
    }

    public function test_artisan_make_owner_and_make_admin_commands(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->artisan('user:make-owner', ['email' => $user->email])
            ->assertSuccessful();

        $this->assertEquals('owner', $user->fresh()->role);

        $this->artisan('user:make-admin', ['email' => $user->email])
            ->assertSuccessful();

        $this->assertEquals('admin', $user->fresh()->role);
    }
}
