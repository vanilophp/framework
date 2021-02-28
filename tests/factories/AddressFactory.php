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
use Faker\Generator as Faker;
use Vanilo\Framework\Models\Address;

$factory->define(Address::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'address' => $faker->address,
        'postalcode' => $faker->postcode,
        'country_id' => $faker->countryCode,
        'city' => $faker->city
    ];
});
