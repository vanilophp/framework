<?php

declare(strict_types=1);

namespace Vanilo\Foundation\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Vanilo\Foundation\Models\MasterProduct;
use Vanilo\Product\Models\ProductState;

class MasterProductFactory extends Factory
{
    protected $model = MasterProduct::class;

    public function definition()
    {
        return [
            'name' => fake()->words(mt_rand(1, 3), true),
            'price' => fake()->numberBetween(10, 2000),
            'state' => ProductState::ACTIVE,
            'description' => fake()->optional(0.9)->paragraph(),
        ];
    }
}
