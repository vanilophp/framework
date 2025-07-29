<?php

declare(strict_types=1);

namespace Vanilo\Cart\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Vanilo\Cart\Tests\Dummies\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'sku' => fake()->unique()->ean8(),
            'name' => fake()->words(mt_rand(1, 3), true),
            'price' => fake()->numberBetween(10, 2000),
        ];
    }
}
