<?php

namespace Tests\Feature;

use App\Enums\LotStatus;
use App\Enums\TraceStage;
use App\Models\Lot;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TraceabilityFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_production_is_visible_on_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson(route('traceability.production.store'), [
                'product_name' => 'Tomates Bio',
                'quantity' => 120,
                'unit' => 'kg',
                'produced_at' => '2026-09-24',
                'origin' => 'Nabeul',
                'location' => 'Nabeul',
                'category' => 'Légumes',
            ])
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->actingAs($user)
            ->get(route('traceability.dashboard'))
            ->assertOk()
            ->assertSee('Tomates Bio');

        $this->assertDatabaseHas('lots', [
            'status' => LotStatus::ACTIVE->value,
        ]);
    }

    public function test_a_lot_can_be_marked_in_transit(): void
    {
        $user = User::factory()->create();

        $product = Product::create([
            'name' => 'Tomates',
            'sku' => 'tomates',
            'category' => 'Légumes',
            'unit' => 'kg',
            'is_active' => true,
        ]);

        $lot = Lot::create([
            'product_id' => $product->id,
            'lot_number' => 'LOT-TRANSIT-001',
            'status' => LotStatus::ACTIVE,
            'quantity' => 100,
            'unit' => 'kg',
            'origin' => 'Nabeul',
            'location' => 'Nabeul',
            'produced_at' => now(),
            'public_token' => 'lot-transit-token',
            'metadata' => [],
        ]);

        $this->actingAs($user)
            ->postJson(route('traceability.lots.transit', $lot), [
                'transport_mode' => 'road',
                'carrier' => 'LogiTunis',
                'origin' => 'Nabeul',
                'destination' => 'Sfax',
            ])
            ->assertOk();

        $lot->refresh();

        $this->assertSame(LotStatus::IN_TRANSIT, $lot->status);
        $this->assertDatabaseHas('trace_events', [
            'lot_id' => $lot->id,
            'stage' => TraceStage::TRANSPORT->value,
            'event_type' => 'IN_TRANSIT',
        ]);
    }
}
