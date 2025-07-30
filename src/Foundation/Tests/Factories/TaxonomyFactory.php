<?php

declare(strict_types=1);

/**
 * Defines the faker factory for the Taxonomy model.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-02-03
 *
 */

namespace Vanilo\Foundation\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Vanilo\Category\Models\Taxonomy;

class TaxonomyFactory extends Factory
{
    protected $model = Taxonomy::class;

    public function definition()
    {
        return [
            'name' => fake()->unique()->word(),
        ];
    }
}
