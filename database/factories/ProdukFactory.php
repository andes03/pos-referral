<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produk>
 */
class ProdukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $products = [
            'Espresso', 'Latte', 'Cappuccino', 'Americano', 'Macchiato',
            'Croissant', 'Bagel', 'Sandwich', 'Salad', 'Pasta',
            'Tiramisu', 'Cheesecake', 'Ice Cream', 'Pudding', 'Cookies'
        ];

        return [
            'id_kategori' => Kategori::factory(),
            'nama' => fake()->randomElement($products),
            'harga' => fake()->randomFloat(2, 10000, 100000),
            'stok' => fake()->numberBetween(0, 100),
            'deskripsi' => fake()->sentence(),
            'image' => null,
        ];
    }
}
