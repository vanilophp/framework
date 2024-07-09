<?php

declare(strict_types=1);

use Faker\Generator as Faker;

$factory->define(\Vanilo\Promotion\Models\Promotion::class, function (Faker $faker) {
    static $password;

    return [
        'code' => $faker->word(),
        'name' => $faker->name(),
        'description' => $faker->text(),
        'priority' => 4,
        'exclusive' => false,
        'usage_limit' => 15,
        'used' => 0,
        'coupon_based' => false,
        'starts_at' => null,
        'ends_at' => null,
        'applies_to_discounted' => false,
    ];
});

