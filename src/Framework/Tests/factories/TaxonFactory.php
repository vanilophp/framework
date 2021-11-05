<?php

declare(strict_types=1);

/**
 * Defines the faker factory for the Taxon model.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-02-03
 *
 */
use Faker\Generator as Faker;
use Vanilo\Category\Models\Taxonomy;
use Vanilo\Framework\Models\Taxon;

$factory->define(Taxon::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'taxonomy_id' => function () {
            return factory(Taxonomy::class)->create()->id;
        },
    ];
});
