<?php
/**
 * Defines the faker factory for the Product model.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-02-03
 *
 */

use Faker\Generator as Faker;
use Vanilo\Framework\Models\Product;
use Vanilo\Product\Models\ProductState;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name'        => $faker->words(mt_rand(1, 3), true),
        'sku'         => $faker->unique()->ean8,
        'price'       => $faker->numberBetween(10, 2000),
        'stock'       => $faker->numberBetween(10, 2000),
        'state'       => ProductState::ACTIVE,
        'description' => $faker->optional(0.9)->paragraph
    ];
});
