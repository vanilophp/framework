<?php

declare(strict_types=1);

/**
 * Defines the faker factory for the Customer model.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-10-26
 *
 */
use Faker\Generator as Faker;
use Konekt\Customer\Models\CustomerType;
use Vanilo\Foundation\Models\Customer;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'type' => CustomerType::ORGANIZATION,
        'company_name' => $faker->company
    ];
});
