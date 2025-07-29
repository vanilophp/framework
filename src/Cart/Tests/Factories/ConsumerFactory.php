<?php

declare(strict_types=1);

namespace Vanilo\Cart\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Vanilo\Cart\Tests\Dummies\Consumer;


class ConsumerFactory extends Factory
{
    protected $model = Consumer::class;

    protected static ?string $password = null;

    public function definition()
    {
        return [
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'password' => static::$password ??= bcrypt('secret'),
            'remember_token' => Str::random(10),
        ];
    }
}
