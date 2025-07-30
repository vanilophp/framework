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

namespace Vanilo\Foundation\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Konekt\Customer\Models\CustomerType;
use Vanilo\Foundation\Models\Customer;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition()
    {
        return [
            'type' => CustomerType::ORGANIZATION,
            'company_name' => fake()->company(),
        ];
    }
}
