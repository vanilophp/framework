<?php

use Faker\Generator as Faker;
use Vanilo\Attributes\Models\Attribute;

$factory->define(Attribute::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'type' => 'text'
    ];
});
