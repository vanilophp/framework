<?php

use Faker\Generator as Faker;
use Vanilo\Cart\Tests\Dummies\Product;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'sku'         => $faker->unique()->ean8,
        'name'        => $faker->words(mt_rand(1, 3), true),
        'price'       => $faker->numberBetween(10, 2000)
    ];
});
