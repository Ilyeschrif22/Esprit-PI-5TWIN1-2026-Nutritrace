<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'producteur',
            'transformateur',
            'distributeur',
            'consommateur',
        ];

        $permissions = [
            'produit.create',
            'produit.view',
            'produit.update',
            'produit.delete',
            'lot.create',
            'lot.view',
            'lot.update',
            'lot.delete',
            'traceabilite_producteur_position.create',
            'traceabilite_producteur_position.view',
            'traceabilite_producteur_position.update',
            'traceabilite_producteur_position.delete',
            'traceabilite_transformateur_position.create',
            'traceabilite_transformateur_position.view',
            'traceabilite_transformateur_position.update',
            'traceabilite_transformateur_position.delete',
            'stockage.create',
            'stockage.view',
            'stockage.update',
            'stockage.delete',
            'traceabilite.view',
            'certification.view',
            'preuve.create',
            'preuve.view',
            'impact.create',
            'impact.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $producteur = Role::firstOrCreate(['name' => 'producteur']);
        $producteur->syncPermissions([
            'produit.create',
            'produit.view',
            'produit.update',
            'produit.delete',
            'lot.create',
            'lot.view',
            'lot.update',
            'lot.delete',
            'traceabilite_producteur_position.create',
            'traceabilite_producteur_position.view',
            'traceabilite_producteur_position.update',
            'traceabilite_producteur_position.delete',
            'preuve.create',
            'preuve.view',
            'impact.create',
            'impact.view',
        ]);

        $transformateur = Role::firstOrCreate(['name' => 'transformateur']);
        $transformateur->syncPermissions([
            'produit.view',
            'lot.view',
            'traceabilite_transformateur_position.create',
            'traceabilite_transformateur_position.view',
            'traceabilite_transformateur_position.update',
            'traceabilite_transformateur_position.delete',
        ]);

        $distributeur = Role::firstOrCreate(['name' => 'distributeur']);
        $distributeur->syncPermissions([
            'produit.view',
            'lot.view',
            'stockage.create',
            'stockage.view',
            'stockage.update',
            'stockage.delete',
            'impact.view',
        ]);

        $consommateur = Role::firstOrCreate(['name' => 'consommateur']);
        $consommateur->syncPermissions([
            'produit.view',
            'lot.view',
            'traceabilite.view',
            'certification.view',
            'preuve.view',
            'impact.view',
        ]);
    }
}
