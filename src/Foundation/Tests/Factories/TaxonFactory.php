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

namespace Vanilo\Foundation\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Vanilo\Foundation\Models\Taxon;

class TaxonFactory extends Factory
{
    protected $model = Taxon::class;

    public function definition()
    {
        return [
            'name' => fake()->unique()->word(),
            'taxonomy_id' => fn () => TaxonomyFactory::new()->create()->id,
        ];
    }
}
