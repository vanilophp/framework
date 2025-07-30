<?php

declare(strict_types=1);

namespace Vanilo\Order\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Konekt\Address\Models\Address;
use Konekt\Address\Models\AddressType;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition()
    {
        return [
            'name' => fake()->name(),
            'type' => AddressType::defaultValue(),
            'country_id' => 'DE',
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
        ];
    }
}
