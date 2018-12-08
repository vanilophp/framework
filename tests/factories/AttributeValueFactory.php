<?php

use Faker\Generator as Faker;
use Vanilo\Attributes\Models\Attribute;
use Vanilo\Attributes\Models\AttributeValue;

$factory->define(AttributeValue::class, function (Faker $faker) {
    $value = $faker->unique()->word;

    return [
        'attribute_id' => function () {
            return factory(Attribute::class)->create()->id;
        },
        'value' => strtolower($value),
        'title' => ucfirst($value)
    ];
});
