<?php
/**
 * Defines the faker factory for the Taxonomy model.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-02-03
 *
 */
use Faker\Generator as Faker;
use Vanilo\Category\Models\Taxonomy;

$factory->define(Taxonomy::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word
    ];
});
