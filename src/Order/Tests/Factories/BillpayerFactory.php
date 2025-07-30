<?php

declare(strict_types=1);

namespace Vanilo\Order\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Vanilo\Order\Models\Billpayer;

class BillpayerFactory extends Factory
{
    protected $model = Billpayer::class;

    public function definition()
    {
        return [
            'email' => fake()->email,
            'phone' => fake()->phoneNumber,
            'firstname' => fake()->firstName,
            'lastname' => fake()->lastName,
            'company_name' => fake()->company,
            'tax_nr' => fake()->countryCode . fake()->randomNumber(7),
            'registration_nr' => fake()->randomAscii . fake()->randomNumber(5) . fake()->randomAscii,
            'is_eu_registered' => fake()->boolean,
            'is_organization' => true,
            'address_id' => fn() => AddressFactory::new()->create()->id,
        ];
    }
}
