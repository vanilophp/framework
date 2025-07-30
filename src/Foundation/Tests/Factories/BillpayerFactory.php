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

namespace Vanilo\Foundation\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Vanilo\Order\Models\Billpayer;

class BillpayerFactory extends Factory
{
    protected $model = Billpayer::class;

    public function definition()
    {
        return [
            'firstname' => fake()->firstName,
            'lastname' => fake()->lastName,
            'company_name' => fake()->company,
        ];
    }
}
