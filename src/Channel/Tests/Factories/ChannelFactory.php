<?php

declare(strict_types=1);

namespace Vanilo\Channel\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Vanilo\Channel\Models\Channel;

class ChannelFactory extends Factory
{
    protected $model = Channel::class;

    public function definition()
    {
        return [
            'name' => fake()->unique()->word(),
        ];
    }
}
