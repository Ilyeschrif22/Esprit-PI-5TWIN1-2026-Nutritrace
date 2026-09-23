<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TraceabilityModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'producteur']);
        Permission::firstOrCreate(['name' => 'traceability.create']);
        Permission::firstOrCreate(['name' => 'traceability.view']);
    }

    public function test_production_trace_can_be_recorded(): void
    {
        $user = User::create([
            'fullname' => 'Test Producteur',
            'email' => 'producteur@example.com',
            'password' => bcrypt('password'),
            'cin' => '12345678',
            'phone' => '22345678',
            'birthdate' => '1990-01-01',
            'governorate' => 'Tunis',
            'city' => 'Tunis',
            'address' => '12 rue Test',
        ]);
        $user->assignRole('producteur');
        $this->actingAs($user);

        $response = $this->post('/traceability/production', [
            'product_name' => 'Tomate',
            'quantity' => 1000,
            'unit' => 'kg',
            'produced_at' => now()->toDateString(),
            'origin' => 'Nabeul',
            'location' => 'Tunisia',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true);
    }

    public function test_traceability_timeline_route_is_available(): void
    {
        $user = User::create([
            'fullname' => 'Test Producteur',
            'email' => 'producteur2@example.com',
            'password' => bcrypt('password'),
            'cin' => '87654321',
            'phone' => '22987654',
            'birthdate' => '1988-01-01',
            'governorate' => 'Sfax',
            'city' => 'Sfax',
            'address' => '7 rue Test',
        ]);
        $user->assignRole('producteur');
        $this->actingAs($user);

        $response = $this->get('/traceability');

        $response->assertStatus(200);
    }

    public function test_login_redirects_to_dashboard_when_route_is_defined(): void
    {
        $user = User::create([
            'fullname' => 'Test User',
            'email' => 'login@example.com',
            'password' => bcrypt('password'),
            'cin' => '11223344',
            'phone' => '22334455',
            'birthdate' => '1991-02-03',
            'governorate' => 'Tunis',
            'city' => 'Tunis',
            'address' => '99 rue Login',
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();
        $user->assignRole('producteur');

        $response = $this->post('/login', [
            'email' => 'login@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
    }
}
