<?php

declare(strict_types=1);

namespace Vanilo\Foundation\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Vanilo\Shipment\Models\ShippingCategory;


class ShippingCategoryFactory extends Factory
{
    protected $model = ShippingCategory::class;

    public function definition()
    {
        return [
            'name' => fake()->words(mt_rand(1, 3), true),
            'is_fragile' => false,
            'is_hazardous' => false,
            'is_stackable' => false,
            'is_not_shippable' => false,
            'requires_temperature_control' => false,
            'requires_signature' => false,
        ];
    }

    public function notShippable(): self
    {
        return $this->state(fn ($attrs) => ['is_not_shippable' => true]);
    }
}
