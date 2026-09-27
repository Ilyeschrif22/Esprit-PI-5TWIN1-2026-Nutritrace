<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;

class UserManagementDemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleAndPermissionSeeder::class);

        $faker = FakerFactory::create('fr_FR');
        $governorates = [
            'Tunis', 'Ariana', 'Sfax', 'Sousse', 'Nabeul',
            'Monastir', 'Bizerte', 'Gabes', 'Kairouan', 'Mahdia',
        ];
        $roles = [
            'admin', 'producteur', 'transformateur', 'distributeur', 'consommateur',
            'producteur', 'transformateur', 'distributeur', 'consommateur', 'admin',
        ];

        for ($index = 1; $index <= 10; $index++) {
            $email = sprintf('demo-user-%02d@example.test', $index);
            $isActive = ! in_array($index, [4, 8], true);
            $governorate = $faker->randomElement($governorates);

            do {
                $cin = $faker->unique()->numerify('########');
            } while (User::where('cin', $cin)->exists());

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'fullname' => $faker->name(),
                    'password' => 'DemoPass!2026',
                    'cin' => $cin,
                    'phone' => $faker->numerify('2#######'),
                    'birthdate' => $faker->dateTimeBetween('-65 years', '-18 years')->format('Y-m-d'),
                    'governorate' => $governorate,
                    'city' => $governorate,
                    'address' => $faker->streetAddress(),
                    'is_active' => $isActive,
                ]
            );

            $user->forceFill([
                'email_verified_at' => $isActive ? now()->subDays($faker->numberBetween(1, 180)) : null,
                'created_at' => $faker->dateTimeBetween('-180 days', 'now'),
            ])->save();
            $user->syncRoles([$roles[$index - 1]]);
        }
    }
}
