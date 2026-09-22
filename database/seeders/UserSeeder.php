<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'fullname'          => 'Ilyes Chrif',
                'email'             => 'ilyes.chrif@nutritrace.com',
                'password'          => 'ilyes1234',
                'email_verified_at' => '2025-09-01 10:00:00',
                'cin'               => '12345678',
                'phone'             => '22123456',
                'birthdate'         => '1995-03-15',
                'governorate'       => 'Tunis',
                'city'              => 'Tunis',
                'address'           => '12 Rue de la Liberté, Tunis',
            ],
            [
                'fullname'          => 'Sana Laaridhi',
                'email'             => 'sana.laaridhi@nutritrace.com',
                'password'          => 'sana1234',
                'email_verified_at' => '2025-09-01 10:00:00',
                'cin'               => '23456789',
                'phone'             => '23234567',
                'birthdate'         => '1997-07-22',
                'governorate'       => 'Sfax',
                'city'              => 'Sfax',
                'address'           => '45 Avenue Habib Bourguiba, Sfax',
            ],
            [
                'fullname'          => 'Abderrahmen Nasri',
                'email'             => 'abderrahmen.nasri@nutritrace.com',
                'password'          => 'abderrahmen1234',
                'email_verified_at' => '2025-09-01 10:00:00',
                'cin'               => '34567890',
                'phone'             => '24345678',
                'birthdate'         => '1993-11-08',
                'governorate'       => 'Sousse',
                'city'              => 'Sousse',
                'address'           => '78 Rue du 14 Janvier, Sousse',
            ],
            [
                'fullname'          => 'Seif Khemiri',
                'email'             => 'seif.khemiri@nutritrace.com',
                'password'          => 'seif1234',
                'email_verified_at' => '2025-09-01 10:00:00',
                'cin'               => '45678901',
                'phone'             => '25456789',
                'birthdate'         => '1996-05-30',
                'governorate'       => 'Monastir',
                'city'              => 'Monastir',
                'address'           => '3 Rue Ibn Khaldoun, Monastir',
            ],
            [
                'fullname'          => 'Saladin Khalfaoui',
                'email'             => 'saladin.khalfaoui@nutritrace.com',
                'password'          => 'saladin1234',
                'email_verified_at' => '2025-09-01 10:00:00',
                'cin'               => '56789012',
                'phone'             => '26567890',
                'birthdate'         => '1994-01-19',
                'governorate'       => 'Nabeul',
                'city'              => 'Nabeul',
                'address'           => '21 Rue de Carthage, Nabeul',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}   