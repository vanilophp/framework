<?php

declare(strict_types=1);

/**
 * Defines the faker factory for the Billpayer model
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-05
 *
 */

use Faker\Generator as Faker;
use Vanilo\Foundation\Models\Address;
use Vanilo\Order\Models\Billpayer;

$factory->define(Billpayer::class, function (Faker $faker) {
    return [
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'company_name' => $faker->company,
        'address_id' => factory(Address::class)->create(),
    ];
});
