<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use Vanilo\Channel\Models\Channel;

$factory->define(Channel::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word
    ];
});
