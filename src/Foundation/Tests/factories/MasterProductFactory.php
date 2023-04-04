<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use Vanilo\Foundation\Models\MasterProduct;
use Vanilo\Product\Models\ProductState;

$factory->define(MasterProduct::class, function (Faker $faker) {
    return [
        'name' => $faker->words(mt_rand(1, 3), true),
        'price' => $faker->numberBetween(10, 2000),
        'state' => ProductState::ACTIVE,
        'description' => $faker->optional(0.9)->paragraph
    ];
});
