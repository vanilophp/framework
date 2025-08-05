<?php

declare(strict_types=1);

/**
 * Defines the faker factory for the Product model.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-02-03
 *
 */

namespace Vanilo\Foundation\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Vanilo\Foundation\Models\Product;
use Vanilo\Product\Models\ProductState;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => fake()->words(mt_rand(1, 3), true),
            'sku' => fake()->unique()->ean8(),
            'price' => fake()->numberBetween(10, 2000),
            'stock' => fake()->numberBetween(10, 2000),
            'state' => ProductState::ACTIVE,
            'description' => fake()->optional(0.9)->paragraph(),
            'shipping_category_id' => null,
        ];
    }
}
