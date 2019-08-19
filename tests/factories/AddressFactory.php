<?php

use Faker\Generator as Faker;
use Konekt\Address\Models\Address;
use Konekt\Address\Models\AddressType;

$factory->define(Address::class, function (Faker $faker) {
    static $password;

    return [
        'name'       => $faker->name,
        'type'       => AddressType::defaultValue(),
        'country_id' => 'DE',
        'address'    => $faker->address,
        'city'       => $faker->city,
    ];
});
