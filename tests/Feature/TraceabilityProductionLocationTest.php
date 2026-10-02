<?php

namespace Tests\Feature;

use App\Models\Location;
use App\Models\Lot;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\User;
use App\Services\TraceabilityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TraceabilityProductionLocationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_producer_cannot_register_a_second_production_location(): void
    {
        $producer = User::factory()->create();
        $service = app(TraceabilityService::class);

        $first = $service->ensureProductionLocation($producer, [
            'name' => 'Ferme de Nabeul',
            'address' => 'Route de Nabeul',
            'city' => 'Nabeul',
            'governorate' => 'Nabeul',
            'latitude' => 36.4511,
            'longitude' => 10.7322,
        ]);

        $second = $service->ensureProductionLocation($producer, [
            'name' => 'Nouvelle ferme',
            'address' => 'Route de Sousse',
            'city' => 'Sousse',
            'governorate' => 'Sousse',
            'latitude' => 35.8254,
            'longitude' => 10.6367,
        ]);

        $this->assertSame($first->id, $second->id);
        $this->assertSame(1, Location::query()->where('user_id', $producer->id)->where('type', 'PRODUCTION_SITE')->count());
    }

    public function test_creating_multiple_lots_for_one_producer_does_not_duplicate_production_locations(): void
    {
        $producer = User::factory()->create();
        $service = app(TraceabilityService::class);

        $service->recordProduction([
            'product_name' => 'Tomates',
            'quantity' => 120,
            'unit' => 'kg',
            'origin' => 'Nabeul',
            'location' => 'Nabeul',
            'category' => 'Légume',
        ], $producer);

        $service->recordProduction([
            'product_name' => 'Olives',
            'quantity' => 80,
            'unit' => 'kg',
            'origin' => 'Nabeul',
            'location' => 'Nabeul',
            'category' => 'Fruit',
        ], $producer);

        $this->assertSame(1, Location::query()->where('user_id', $producer->id)->where('type', 'PRODUCTION_SITE')->count());
        $this->assertSame(2, Lot::query()->where('origin', 'Nabeul')->count());
    }

    public function test_updating_a_producer_location_updates_the_existing_record(): void
    {
        $producer = User::factory()->create();
        $service = app(TraceabilityService::class);

        $location = $service->ensureProductionLocation($producer, [
            'name' => 'Ferme initiale',
            'city' => 'Nabeul',
            'address' => 'Ancienne adresse',
            'governorate' => 'Nabeul',
            'latitude' => 36.4511,
            'longitude' => 10.7322,
        ]);

        $updated = $service->ensureProductionLocation($producer, [
            'name' => 'Ferme mise à jour',
            'city' => 'Nabeul',
            'address' => 'Nouvelle adresse',
            'governorate' => 'Nabeul',
            'latitude' => 36.46,
            'longitude' => 10.74,
        ]);

        $this->assertSame($location->id, $updated->id);
        $this->assertSame('Ferme mise à jour', $updated->fresh()->name);
        $this->assertSame('Nouvelle adresse', $updated->fresh()->address);
    }

    public function test_a_valid_in_transit_lot_produces_an_origin_to_destination_route(): void
    {
        $producer = User::factory()->create();
        $service = app(TraceabilityService::class);

        $lot = $service->recordProduction([
            'product_name' => 'Tomates',
            'quantity' => 120,
            'unit' => 'kg',
            'origin' => 'Nabeul',
            'location' => 'Nabeul',
            'category' => 'Légume',
        ], $producer);

        $service->recordTransit($lot, [
            'destination' => 'Sfax',
            'transport_mode' => 'road',
            'carrier' => 'LogiTun',
        ], $producer);

        $shipment = Shipment::query()->where('lot_id', $lot->id)->first();
        $this->assertNotNull($shipment);
        $this->assertSame('Sfax', $shipment->destination?->name ?? $lot->metadata['transit']['destination'] ?? null);
        $this->assertNotNull($shipment->origin_location_id);
        $this->assertNotNull($shipment->destination_location_id);
    }
}
