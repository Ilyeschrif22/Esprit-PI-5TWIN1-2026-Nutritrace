<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gouvernorats = [
            'Ariana', 'Béja', 'Ben Arous', 'Bizerte', 'Gabès', 'Gafsa',
            'Jendouba', 'Kairouan', 'Kasserine', 'Kébili', 'Le Kef', 'Mahdia',
            'La Manouba', 'Médenine', 'Monastir', 'Nabeul', 'Sfax', 'Sidi Bouzid',
            'Siliana', 'Sousse', 'Tataouine', 'Tozeur', 'Tunis', 'Zaghouan',
        ];

        return [
            'name' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'cin' => fake()->unique()->numerify('########'),
            'telephone' => '2'.fake()->numerify('#######'),
            'date_naissance' => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            'genre' => fake()->randomElement(['homme', 'femme']),
            'gouvernorat' => fake()->randomElement($gouvernorats),
            'delegation' => fake()->city(),
            'ville' => fake()->city(),
            'adresse' => fake()->streetAddress(),
            'code_postal' => fake()->numerify('####'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
