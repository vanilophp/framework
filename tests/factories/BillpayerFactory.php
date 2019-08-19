<?php

use Faker\Generator as Faker;
use Konekt\Address\Models\Address;
use Vanilo\Order\Models\Billpayer;

$factory->define(Billpayer::class, function (Faker $faker) {
    static $password;

    return [
        'email'            => $faker->email,
        'phone'            => $faker->phoneNumber,
        'firstname'        => $faker->firstName,
        'lastname'         => $faker->lastName,
        'company_name'     => $faker->company,
        'tax_nr'           => $faker->countryCode . $faker->randomNumber(7),
        'registration_nr'  => $faker->randomAscii . $faker->randomNumber(5) . $faker->randomAscii,
        'is_eu_registered' => $faker->boolean,
        'is_organization'  => true,
        'address_id'       => function () {
            return factory(Address::class)->create()->id;
        },
    ];
});
