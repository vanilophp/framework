<?php

declare(strict_types=1);

namespace Vanilo\Order\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Konekt\User\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    private static ?string $password = null;

    public function definition()
    {
        return [
            'name' => fake()->name,
            'email' => fake()->randomAscii . fake()->randomAscii . fake()->randomNumber(5) . '.' . fake()->unique()->safeEmail,
            'password' => self::$password ??= bcrypt('secret'),
            'remember_token' => Str::random(10),
        ];
    }
}
