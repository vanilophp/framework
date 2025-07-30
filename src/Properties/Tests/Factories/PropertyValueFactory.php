<?php

declare(strict_types=1);

namespace Vanilo\Properties\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Vanilo\Properties\Models\PropertyValue;

class PropertyValueFactory extends Factory
{
    protected $model = PropertyValue::class;

    public function definition()
    {
        $value = fake()->unique()->word();

        return [
            'property_id' => fn () => PropertyFactory::new()->create()->id,
            'value' => strtolower($value),
            'title' => ucfirst($value)
        ];
    }
}
