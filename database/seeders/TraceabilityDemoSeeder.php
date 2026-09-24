<?php

namespace Database\Seeders;

use App\Enums\LotStatus;
use App\Enums\TraceAlertSeverity;
use App\Enums\TraceAlertType;
use App\Enums\TraceEventStatus;
use App\Enums\TraceStage;
use App\Models\ColdChainLog;
use App\Models\Location;
use App\Models\Lot;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\TraceAlert;
use App\Models\TraceEvent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TraceabilityDemoSeeder extends Seeder
{
    public function run(): void
    {
        $producer = User::updateOrCreate(
            ['email' => 'producteur.demo@example.com'],
            [
                'fullname' => 'Producteur Demo',
                'password' => bcrypt('password'),
                'cin' => '10000001',
                'phone' => '20000001',
                'birthdate' => '1990-01-01',
                'governorate' => 'Nabeul',
                'city' => 'Nabeul',
                'address' => 'Route de Nabeul',
                'email_verified_at' => now(),
            ]
        );
        $producer->assignRole('producteur');

        $distributor = User::updateOrCreate(
            ['email' => 'distributeur.demo@example.com'],
            [
                'fullname' => 'Distributeur Demo',
                'password' => bcrypt('password'),
                'cin' => '10000002',
                'phone' => '20000002',
                'birthdate' => '1988-02-02',
                'governorate' => 'Tunis',
                'city' => 'Tunis',
                'address' => 'Avenue de la Logistique',
                'email_verified_at' => now(),
            ]
        );
        $distributor->assignRole('distributeur');

        $locations = [
            ['name' => 'Ferme de Nabeul', 'type' => 'PRODUCTION_SITE', 'city' => 'Nabeul', 'governorate' => 'Nabeul', 'address' => 'Zone agricole Nabeul', 'latitude' => 36.4511, 'longitude' => 10.7322],
            ['name' => 'Atelier de Transformation', 'type' => 'TRANSFORMATION_SITE', 'city' => 'Sousse', 'governorate' => 'Sousse', 'address' => 'Usine de transformation', 'latitude' => 35.8254, 'longitude' => 10.6367],
            ['name' => 'Chambre Froide Tunis', 'type' => 'COLD_ROOM', 'city' => 'Tunis', 'governorate' => 'Tunis', 'address' => 'Zone logistique Tunis', 'latitude' => 36.8065, 'longitude' => 10.1815],
            ['name' => 'Entrepôt de Sfax', 'type' => 'WAREHOUSE', 'city' => 'Sfax', 'governorate' => 'Sfax', 'address' => 'Warehouse Sfax', 'latitude' => 34.7406, 'longitude' => 10.7604],
            ['name' => 'Centre Distribution Tunis', 'type' => 'DISTRIBUTION_CENTER', 'city' => 'Tunis', 'governorate' => 'Tunis', 'address' => 'Centre de distribution', 'latitude' => 36.8065, 'longitude' => 10.1815],
            ['name' => 'Point de Vente Sousse', 'type' => 'RETAIL_POINT', 'city' => 'Sousse', 'governorate' => 'Sousse', 'address' => 'Magasin Sousse', 'latitude' => 35.8254, 'longitude' => 10.6367],
        ];

        $createdLocations = [];
        foreach ($locations as $location) {
            $createdLocations[] = Location::updateOrCreate(
                ['name' => $location['name']],
                $location
            );
        }

        $products = [
            ['name' => 'Tomates', 'sku' => 'TOM-001', 'category' => 'Légume', 'unit' => 'kg', 'description' => 'Tomates locales', 'is_active' => true],
            ['name' => 'Olives', 'sku' => 'OLI-002', 'category' => 'Fruit sec', 'unit' => 'kg', 'description' => 'Olives biologiques', 'is_active' => true],
            ['name' => 'Poivrons', 'sku' => 'POI-003', 'category' => 'Légume', 'unit' => 'kg', 'description' => 'Poivrons rouges', 'is_active' => true],
            ['name' => 'Oranges', 'sku' => 'ORA-004', 'category' => 'Fruit', 'unit' => 'kg', 'description' => 'Oranges de Sfax', 'is_active' => true],
        ];

        $createdProducts = [];
        foreach ($products as $product) {
            $createdProducts[] = Product::updateOrCreate(
                ['sku' => $product['sku']],
                $product
            );
        }

        $lots = [
            ['lot_number' => 'LOT-NT-2026-001', 'product_id' => $createdProducts[0]->id, 'status' => LotStatus::ACTIVE, 'quantity' => 500, 'unit' => 'kg', 'origin' => 'Nabeul', 'location' => 'Nabeul', 'produced_at' => now()->subDays(12), 'public_token' => 'demo-token-001'],
            ['lot_number' => 'LOT-NT-2026-002', 'product_id' => $createdProducts[0]->id, 'status' => LotStatus::STORED, 'quantity' => 320, 'unit' => 'kg', 'origin' => 'Nabeul', 'location' => 'Tunis', 'produced_at' => now()->subDays(15), 'public_token' => 'demo-token-002'],
            ['lot_number' => 'LOT-NT-2026-003', 'product_id' => $createdProducts[1]->id, 'status' => LotStatus::IN_TRANSIT, 'quantity' => 210, 'unit' => 'kg', 'origin' => 'Sousse', 'location' => 'Sousse', 'produced_at' => now()->subDays(8), 'public_token' => 'demo-token-003'],
            ['lot_number' => 'LOT-NT-2026-004', 'product_id' => $createdProducts[2]->id, 'status' => LotStatus::ACTIVE, 'quantity' => 180, 'unit' => 'kg', 'origin' => 'Tunis', 'location' => 'Tunis', 'produced_at' => now()->subDays(5), 'public_token' => 'demo-token-004'],
            ['lot_number' => 'LOT-NT-2026-005', 'product_id' => $createdProducts[3]->id, 'status' => LotStatus::BLOCKED, 'quantity' => 90, 'unit' => 'kg', 'origin' => 'Sfax', 'location' => 'Sfax', 'produced_at' => now()->subDays(10), 'public_token' => 'demo-token-005'],
        ];

        $createdLots = [];
        foreach ($lots as $lotData) {
            $createdLots[] = Lot::updateOrCreate(
                ['lot_number' => $lotData['lot_number']],
                array_merge($lotData, ['metadata' => ['seeded' => true]])
            );
        }

        foreach ($createdLots as $index => $lot) {
            TraceEvent::create([
                'lot_id' => $lot->id,
                'actor_id' => $producer->id,
                'stage' => TraceStage::PRODUCTION,
                'event_type' => 'PRODUCTION',
                'occurred_at' => $lot->produced_at ?? now(),
                'quantity' => $lot->quantity,
                'unit' => $lot->unit,
                'status' => TraceEventStatus::VALIDATED,
                'latitude' => $createdLocations[$index % count($createdLocations)]->latitude,
                'longitude' => $createdLocations[$index % count($createdLocations)]->longitude,
                'metadata' => ['source' => 'seed'],
            ]);

            if ($index % 2 === 0) {
                TraceEvent::create([
                    'lot_id' => $lot->id,
                    'actor_id' => $distributor->id,
                    'stage' => TraceStage::TRANSPORT,
                    'event_type' => 'DISPATCHED',
                    'occurred_at' => now()->subDays($index + 2),
                    'quantity' => $lot->quantity,
                    'unit' => $lot->unit,
                    'status' => TraceEventStatus::VALIDATED,
                    'latitude' => $createdLocations[1]->latitude,
                    'longitude' => $createdLocations[1]->longitude,
                    'metadata' => ['shipment_reference' => 'SHIP-DEMO-' . ($index + 1)],
                ]);
            }

            if ($lot->status === LotStatus::BLOCKED) {
                TraceAlert::create([
                    'lot_id' => $lot->id,
                    'trace_event_id' => $lot->events()->first()?->id,
                    'type' => TraceAlertType::STATUS,
                    'severity' => TraceAlertSeverity::HIGH,
                    'message' => 'Le lot a été bloqué à des fins de vérification.',
                    'status' => 'open',
                    'detected_at' => now()->subDay(),
                    'metadata' => ['source' => 'seed'],
                ]);
            }
        }

        foreach ([
            ['lot_id' => $createdLots[0]->id, 'reference' => 'SHIP-2026-001', 'origin_location_id' => $createdLocations[0]->id, 'destination_location_id' => $createdLocations[4]->id, 'transport_mode' => 'refrigerated_road', 'carrier' => 'LogiTun', 'vehicle_reference' => 'TN-101', 'distance_km' => 145.6, 'departed_at' => now()->subDays(3), 'expected_arrival_at' => now()->subDays(2), 'status' => 'arrived'],
            ['lot_id' => $createdLots[1]->id, 'reference' => 'SHIP-2026-002', 'origin_location_id' => $createdLocations[2]->id, 'destination_location_id' => $createdLocations[5]->id, 'transport_mode' => 'road', 'carrier' => 'Atlas Distribution', 'vehicle_reference' => 'TN-202', 'distance_km' => 84.2, 'departed_at' => now()->subDays(7), 'expected_arrival_at' => now()->subDays(6), 'status' => 'in_transit'],
            ['lot_id' => $createdLots[2]->id, 'reference' => 'SHIP-2026-003', 'origin_location_id' => $createdLocations[1]->id, 'destination_location_id' => $createdLocations[4]->id, 'transport_mode' => 'refrigerated_road', 'carrier' => 'Fresh Route', 'vehicle_reference' => 'TN-303', 'distance_km' => 120.3, 'departed_at' => now()->subDays(4), 'expected_arrival_at' => now()->subDays(3), 'status' => 'in_transit'],
        ] as $shipmentData) {
            Shipment::updateOrCreate(
                ['reference' => $shipmentData['reference']],
                $shipmentData
            );
        }

        foreach ($createdLots as $lot) {
            ColdChainLog::create([
                'lot_id' => $lot->id,
                'location_id' => $createdLocations[2]->id,
                'action' => 'COLD_STORAGE_ENTRY',
                'temperature_c' => 4.2,
                'occurred_at' => now()->subDays(2),
                'notes' => 'Entrée en chambre froide',
            ]);
        }

        $this->command->info('Données de démonstration de traçabilité créées avec succès.');
    }
}
