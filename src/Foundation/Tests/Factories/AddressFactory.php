<?php

declare(strict_types=1);

/**
 * Defines the faker factory for the Address model.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-10-26
 *
 */

namespace Vanilo\Foundation\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Vanilo\Foundation\Models\Address;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition()
    {
        return [
            'name' => fake()->name,
            'address' => fake()->address,
            'postalcode' => fake()->postcode,
            'country_id' => fake()->countryCode,
            'city' => fake()->city,
        ];
    }
}
