<?php

namespace Tests\Feature;

use App\Models\ColdChainLog;
use App\Models\Lot;
use App\Models\Location;
use App\Models\Product;
use App\Models\TraceEvent;
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
        Role::firstOrCreate(['name' => 'distributeur']);
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

    public function test_public_traceability_route_returns_lot_details(): void
    {
        $user = User::create([
            'fullname' => 'Trace User',
            'email' => 'trace@example.com',
            'password' => bcrypt('password'),
            'cin' => '11111111',
            'phone' => '20000000',
            'birthdate' => '1990-01-01',
            'governorate' => 'Nabeul',
            'city' => 'Nabeul',
            'address' => '22 rue Trace',
        ]);
        $user->assignRole('producteur');

        $product = Product::create([
            'name' => 'Tomate',
            'sku' => 'TOM-001',
            'category' => 'Légume',
            'description' => 'Tomate locale',
            'unit' => 'kg',
            'is_active' => true,
        ]);

        $lot = Lot::create([
            'product_id' => $product->id,
            'lot_number' => 'LOT-TRACE-001',
            'status' => 'active',
            'quantity' => 120,
            'unit' => 'kg',
            'origin' => 'Nabeul',
            'location' => 'Tunisie',
            'produced_at' => now(),
            'public_token' => 'trace-public-token',
        ]);

        TraceEvent::create([
            'lot_id' => $lot->id,
            'actor_id' => $user->id,
            'stage' => 'production',
            'event_type' => 'PRODUCTION',
            'occurred_at' => now(),
            'quantity' => 120,
            'unit' => 'kg',
            'status' => 'validated',
            'metadata' => ['origin' => 'Nabeul'],
        ]);

        $response = $this->get('/trace/trace-public-token');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.lot_number', 'LOT-TRACE-001')
            ->assertJsonPath('data.product.name', 'Tomate');
    }

    public function test_shipment_creation_route_is_available(): void
    {
        $user = User::create([
            'fullname' => 'Shipment User',
            'email' => 'shipment@example.com',
            'password' => bcrypt('password'),
            'cin' => '22222222',
            'phone' => '20000001',
            'birthdate' => '1992-02-02',
            'governorate' => 'Sousse',
            'city' => 'Sousse',
            'address' => '14 rue Ship',
        ]);
        $user->assignRole('distributeur');
        $this->actingAs($user);

        $product = Product::create([
            'name' => 'Olive',
            'sku' => 'OLI-002',
            'category' => 'Produit',
            'description' => 'Olive biologique',
            'unit' => 'kg',
            'is_active' => true,
        ]);

        $lot = Lot::create([
            'product_id' => $product->id,
            'lot_number' => 'LOT-SHIP-001',
            'status' => 'active',
            'quantity' => 50,
            'unit' => 'kg',
            'origin' => 'Sousse',
            'location' => 'Tunisie',
            'produced_at' => now(),
            'public_token' => 'shipment-public-token',
        ]);

        $origin = Location::create([
            'name' => 'Sousse Warehouse',
            'type' => 'WAREHOUSE',
            'city' => 'Sousse',
            'governorate' => 'Sousse',
            'latitude' => 35.8254,
            'longitude' => 10.6367,
        ]);

        $destination = Location::create([
            'name' => 'Tunis Retail',
            'type' => 'DISTRIBUTION_CENTER',
            'city' => 'Tunis',
            'governorate' => 'Tunis',
            'latitude' => 36.8065,
            'longitude' => 10.1815,
        ]);

        $response = $this->post('/traceability/shipments', [
            'lot_id' => $lot->id,
            'reference' => 'SHIP-2026-001',
            'origin_location_id' => $origin->id,
            'destination_location_id' => $destination->id,
            'transport_mode' => 'refrigerated_road',
            'carrier' => 'LogiTun',
            'vehicle_reference' => 'TN-456',
            'distance_km' => 140,
            'departed_at' => now()->toDateTimeString(),
            'expected_arrival_at' => now()->addDay()->toDateTimeString(),
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('shipment.reference', 'SHIP-2026-001');
    }

    public function test_transformation_route_is_available(): void
    {
        $user = User::create([
            'fullname' => 'Transform User',
            'email' => 'transform@example.com',
            'password' => bcrypt('password'),
            'cin' => '33333333',
            'phone' => '20000002',
            'birthdate' => '1989-03-03',
            'governorate' => 'Nabeul',
            'city' => 'Nabeul',
            'address' => '15 rue Transform',
        ]);
        $user->assignRole('producteur');
        $this->actingAs($user);

        $product = Product::create([
            'name' => 'Tomate',
            'sku' => 'TOM-TRANSFORM',
            'category' => 'Légume',
            'description' => 'Tomate',
            'unit' => 'kg',
            'is_active' => true,
        ]);

        $inputLot = Lot::create([
            'product_id' => $product->id,
            'lot_number' => 'LOT-INPUT-001',
            'status' => 'active',
            'quantity' => 100,
            'unit' => 'kg',
            'origin' => 'Nabeul',
            'location' => 'Tunisie',
            'produced_at' => now(),
            'public_token' => 'transform-input-token',
        ]);

        $location = Location::create([
            'name' => 'Unit Transform',
            'type' => 'TRANSFORMATION_SITE',
            'city' => 'Nabeul',
            'governorate' => 'Nabeul',
            'latitude' => 36.4511,
            'longitude' => 10.7322,
        ]);

        $response = $this->post('/traceability/transformations', [
            'input_lot_id' => $inputLot->id,
            'process_name' => 'Pulpe',
            'output_quantity' => 80,
            'loss_quantity' => 20,
            'location_id' => $location->id,
            'occurred_at' => now()->toDateTimeString(),
            'notes' => 'Transformation préparée',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('transformation.process_name', 'Pulpe');
    }

    public function test_cold_chain_route_is_available(): void
    {
        $user = User::create([
            'fullname' => 'Cold User',
            'email' => 'cold@example.com',
            'password' => bcrypt('password'),
            'cin' => '44444444',
            'phone' => '20000003',
            'birthdate' => '1993-04-04',
            'governorate' => 'Tunis',
            'city' => 'Tunis',
            'address' => '30 rue Cold',
        ]);
        $user->assignRole('producteur');
        $this->actingAs($user);

        $product = Product::create([
            'name' => 'Poivron',
            'sku' => 'POI-001',
            'category' => 'Légume',
            'description' => 'Poivron',
            'unit' => 'kg',
            'is_active' => true,
        ]);

        $lot = Lot::create([
            'product_id' => $product->id,
            'lot_number' => 'LOT-COLD-001',
            'status' => 'active',
            'quantity' => 75,
            'unit' => 'kg',
            'origin' => 'Tunis',
            'location' => 'Tunisie',
            'produced_at' => now(),
            'public_token' => 'cold-token',
        ]);

        $location = Location::create([
            'name' => 'Cold Room A',
            'type' => 'COLD_ROOM',
            'city' => 'Tunis',
            'governorate' => 'Tunis',
            'latitude' => 36.8065,
            'longitude' => 10.1815,
        ]);

        $response = $this->post('/traceability/cold-chain', [
            'lot_id' => $lot->id,
            'location_id' => $location->id,
            'action' => 'COLD_STORAGE_ENTRY',
            'temperature_c' => 4.5,
            'occurred_at' => now()->toDateTimeString(),
            'notes' => 'Entrée en chambre froide',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('event.action', 'COLD_STORAGE_ENTRY');
    }

    public function test_map_route_returns_points_for_lot(): void
    {
        $user = User::create([
            'fullname' => 'Map User',
            'email' => 'map@example.com',
            'password' => bcrypt('password'),
            'cin' => '55555555',
            'phone' => '20000004',
            'birthdate' => '1994-05-05',
            'governorate' => 'Sfax',
            'city' => 'Sfax',
            'address' => '12 rue Map',
        ]);
        $user->assignRole('producteur');
        $this->actingAs($user);

        $product = Product::create([
            'name' => 'Orange',
            'sku' => 'ORA-001',
            'category' => 'Fruit',
            'description' => 'Orange locale',
            'unit' => 'kg',
            'is_active' => true,
        ]);

        $lot = Lot::create([
            'product_id' => $product->id,
            'lot_number' => 'LOT-MAP-001',
            'status' => 'active',
            'quantity' => 60,
            'unit' => 'kg',
            'origin' => 'Sfax',
            'location' => 'Tunisie',
            'produced_at' => now(),
            'public_token' => 'map-token',
        ]);

        TraceEvent::create([
            'lot_id' => $lot->id,
            'actor_id' => $user->id,
            'stage' => 'production',
            'event_type' => 'PRODUCTION',
            'occurred_at' => now(),
            'quantity' => 60,
            'unit' => 'kg',
            'status' => 'validated',
            'latitude' => 34.7406,
            'longitude' => 10.7604,
            'metadata' => ['origin' => 'Sfax'],
        ]);

        $response = $this->get('/traceability/lots/' . $lot->id . '/map');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.lot_number', 'LOT-MAP-001');
    }
}
