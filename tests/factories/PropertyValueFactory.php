<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use Vanilo\Properties\Models\Property;
use Vanilo\Properties\Models\PropertyValue;

$factory->define(PropertyValue::class, function (Faker $faker) {
    $value = $faker->unique()->word;

    return [
        'property_id' => function () {
            return factory(Property::class)->create()->id;
        },
        'value' => strtolower($value),
        'title' => ucfirst($value)
    ];
});
