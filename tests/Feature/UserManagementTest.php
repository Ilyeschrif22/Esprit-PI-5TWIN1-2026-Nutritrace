<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\JwtService;
use Database\Seeders\UserManagementDemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\DatabaseSeeder;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_any_signed_in_role_can_open_the_user_directory(): void
    {
        $user = $this->createUser('consommateur', 'consumer@example.test');

        $this->actingAs($user)
            ->get(route('utilisateurs'))
            ->assertOk()
            ->assertSee('Utilisateurs');
    }

    public function test_admin_can_view_the_user_directory(): void
    {
        $admin = $this->createUser('admin', 'admin@example.test');
        $this->createUser('consommateur', 'consumer@example.test');

        $this->actingAs($admin)
            ->get(route('utilisateurs', ['search' => 'Test']))
            ->assertOk()
            ->assertSee('Utilisateurs')
            ->assertSee('consumer@example.test');
    }

    public function test_admin_can_create_a_user_with_a_role(): void
    {
        $admin = $this->createUser('admin', 'admin@example.test');
        Role::findOrCreate('producteur', 'web');

        $this->actingAs($admin)
            ->post(route('utilisateurs.store'), [
                'fullname' => 'Nadia Ben Ali',
                'email' => 'nadia@example.test',
                'phone' => '22123456',
                'cin' => '12345678',
                'role' => 'producteur',
                'is_active' => '1',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ])
            ->assertRedirect(route('utilisateurs'))
            ->assertSessionHas('status');

        $createdUser = User::where('email', 'nadia@example.test')->firstOrFail();
        $this->assertSame('Nadia Ben Ali', $createdUser->fullname);
        $this->assertTrue($createdUser->is_active);
        $this->assertTrue(Hash::check('password123', $createdUser->password));
        $this->assertTrue($createdUser->hasRole('producteur'));
    }

    public function test_admin_can_edit_and_deactivate_a_user(): void
    {
        $admin = $this->createUser('admin', 'admin@example.test');
        $target = $this->createUser('consommateur', 'target@example.test');
        Role::findOrCreate('distributeur', 'web');

        $this->actingAs($admin)
            ->put(route('utilisateurs.update', $target), [
                'fullname' => 'Updated User',
                'email' => 'target@example.test',
                'phone' => '',
                'cin' => '',
                'role' => 'distributeur',
                'is_active' => '0',
                'password' => '',
                'password_confirmation' => '',
            ])
            ->assertRedirect(route('utilisateurs'))
            ->assertSessionHas('status');

        $target->refresh();
        $this->assertSame('Updated User', $target->fullname);
        $this->assertFalse($target->is_active);
        $this->assertTrue($target->hasRole('distributeur'));
    }

    public function test_signed_in_user_can_toggle_another_accounts_status(): void
    {
        $operator = $this->createUser('consommateur', 'operator@example.test');
        $target = $this->createUser('producteur', 'target@example.test');

        $this->actingAs($operator)
            ->patch(route('utilisateurs.status', $target))
            ->assertRedirect(route('utilisateurs'))
            ->assertSessionHas('status', 'Compte désactivé.');

        $this->assertFalse($target->fresh()->is_active);

        $this->patch(route('utilisateurs.status', $target))
            ->assertSessionHas('status', 'Compte activé.');

        $this->assertTrue($target->fresh()->is_active);
    }

    public function test_account_cannot_deactivate_itself(): void
    {
        $user = $this->createUser('consommateur', 'self@example.test');

        $this->actingAs($user)
            ->patch(route('utilisateurs.status', $user))
            ->assertSessionHasErrors('status');

        $this->assertTrue($user->fresh()->is_active);
    }

    public function test_account_cannot_deactivate_itself_through_the_edit_form(): void
    {
        $user = $this->createUser('consommateur', 'self@example.test');

        $this->actingAs($user)
            ->put(route('utilisateurs.update', $user), [
                'fullname' => $user->fullname,
                'email' => $user->email,
                'phone' => '',
                'cin' => '',
                'role' => 'consommateur',
                'is_active' => '0',
                'password' => '',
                'password_confirmation' => '',
            ])
            ->assertSessionHasErrors('is_active');

        $this->assertTrue($user->fresh()->is_active);
    }

    public function test_existing_web_session_is_logged_out_after_deactivation(): void
    {
        $operator = $this->createUser('consommateur', 'operator@example.test');
        $target = $this->createUser('producteur', 'target@example.test');

        $this->actingAs($operator)
            ->patch(route('utilisateurs.status', $target))
            ->assertRedirect(route('utilisateurs'));

        $target->refresh();
        $this->actingAs($target)
            ->get(route('dashboard'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');
    }

    public function test_admin_can_delete_a_user_but_not_themselves(): void
    {
        $admin = $this->createUser('admin', 'admin@example.test');
        $target = $this->createUser('consommateur', 'target@example.test');

        $this->actingAs($admin)
            ->delete(route('utilisateurs.destroy', $target))
            ->assertRedirect(route('utilisateurs'));

        $this->assertDatabaseMissing('users', ['id' => $target->id]);

        $this->delete(route('utilisateurs.destroy', $admin))
            ->assertRedirect()
            ->assertSessionHasErrors('delete');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_inactive_users_cannot_sign_in(): void
    {
        $user = $this->createUser('consommateur', 'inactive@example.test');
        $token = app(JwtService::class)->generateToken($user);
        $user->update(['is_active' => false]);

        $this->post(route('login'), [
            'email' => 'inactive@example.test',
            'password' => 'password123',
        ])->assertSessionHasErrors('email');

        $this->postJson(route('api.login'), [
            'email' => 'inactive@example.test',
            'password' => 'password123',
        ])->assertForbidden();

        $this->withToken($token)
            ->getJson(route('api.me'))
            ->assertForbidden();
    }

    public function test_default_database_seeder_creates_roles_for_role_selection(): void
    {
        $this->seed(DatabaseSeeder::class);
        $user = User::create([
            'fullname' => 'New Consumer',
            'email' => 'new-consumer@example.test',
            'password' => 'password123',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->post(route('role-selection.store'), ['role' => 'consommateur'])
            ->assertRedirect(route('dashboard'));

        $this->assertTrue($user->fresh()->hasRole('consommateur'));
    }

    public function test_demo_user_seeder_creates_ten_varied_accounts_without_duplicates(): void
    {
        $this->seed(UserManagementDemoSeeder::class);

        $demoUsers = User::where('email', 'like', 'demo-user-%@example.test');
        $this->assertSame(10, $demoUsers->count());
        $this->assertSame(8, (clone $demoUsers)->where('is_active', true)->count());
        $this->assertSame(2, (clone $demoUsers)->where('is_active', false)->count());

        $this->seed(UserManagementDemoSeeder::class);

        $this->assertSame(10, User::where('email', 'like', 'demo-user-%@example.test')->count());
    }

    private function createUser(string $role, string $email): User
    {
        Role::findOrCreate($role, 'web');

        $user = User::create([
            'fullname' => 'Test User',
            'email' => $email,
            'password' => 'password123',
            'is_active' => true,
        ]);
        $user->assignRole($role);

        return $user;
    }
}