<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use Vanilo\Foundation\Models\MasterProduct;
use Vanilo\Foundation\Models\MasterProductVariant;
use Vanilo\Product\Models\ProductState;

$factory->define(MasterProductVariant::class, function (Faker $faker) {
    return [
        'name' => $faker->words(mt_rand(1, 3), true),
        'master_product_id' => function () {
            return factory(MasterProduct::class)->create()->id;
        },
        'sku' => $faker->unique()->ean8,
        'price' => $faker->numberBetween(10, 2000),
        'stock' => $faker->numberBetween(10, 2000),
        'state' => ProductState::ACTIVE,
        'description' => $faker->optional(0.1)->paragraph
    ];
});
