<?php

declare(strict_types=1);

namespace Vanilo\Properties\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Vanilo\Properties\Models\Property;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition()
    {
        return [
            'name' => fake()->unique()->word(),
            'type' => 'text',
        ];
    }
}
