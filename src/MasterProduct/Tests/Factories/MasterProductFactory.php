<?php

declare(strict_types=1);

/**
 * Contains the MasterProductFactory class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-11-29
 *
 */

namespace Vanilo\MasterProduct\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Vanilo\MasterProduct\Models\MasterProduct;
use Vanilo\Product\Models\ProductStateProxy;

class MasterProductFactory extends Factory
{
    protected $model = MasterProduct::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(mt_rand(2, 5), true),
            'state' => ProductStateProxy::defaultValue(),
        ];
    }

    public function inactive(): self
    {
        return $this->state(fn ($attrs) => ['state' => ProductStateProxy::INACTIVE()]);
    }

    public function active(): self
    {
        return $this->state(fn ($attrs) => ['state' => ProductStateProxy::ACTIVE()]);
    }
}
