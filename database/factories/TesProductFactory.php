<?php

namespace Database\Factories;

use App\Models\TesProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class TesProductFactory extends Factory
{
    protected $model = TesProduct::class;

    public function definition(): array
    {
        return [
            'sku' => fake()->unique()->bothify('PROD-####'),
            'name' => fake()->randomElement(['Kemeja', 'Sepatu', 'Tas', 'Laptop', 'Kopi', 'Buku', 'Helm', 'Jam Tangan', 'Charger', 'Mouse']) . ' ' . fake()->company(),
            'description' => fake()->sentence(10),
            'stock' => fake()->numberBetween(0, 500),
            'price' => fake()->randomFloat(0, 50000, 5000000),
            'category' => fake()->randomElement(['Elektronik', 'Fashion', 'Makanan', 'Kantor']),
            'is_active' => fake()->boolean(80),
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
