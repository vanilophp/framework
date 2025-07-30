<?php

declare(strict_types=1);

namespace Vanilo\Foundation\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Vanilo\Foundation\Models\MasterProductVariant;
use Vanilo\Product\Models\ProductState;

class MasterProductVariantFactory extends Factory
{
    protected $model = MasterProductVariant::class;

    public function definition()
    {
        return [
            'name' => fake()->words(mt_rand(1, 3), true),
            'master_product_id' => fn () => MasterProductFactory::new()->create()->id,
            'sku' => fake()->unique()->ean8(),
            'price' => fake()->numberBetween(10, 2000),
            'stock' => fake()->numberBetween(10, 2000),
            'state' => ProductState::ACTIVE,
            'description' => fake()->optional(0.1)->paragraph(),
        ];
    }
}
