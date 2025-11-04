<?php

namespace Database\Factories;

use App\Models\Pelanggan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pelanggan>
 */
class PelangganFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'no_telp' => fake()->phoneNumber(),
            'alamat' => fake()->address(),
            'poin' => fake()->numberBetween(0, 1000),
            'kode_referal' => Pelanggan::generateUniqueReferralCode(),
            'image' => null,
        ];
    }

    /**
     * Indicate that the pelanggan used a referral code.
     */
    public function withReferral(string $code): static
    {
        return $this->state(fn (array $attributes) => [
            'kode_referal_digunakan' => $code,
        ]);
    }
}
