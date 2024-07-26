<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Vanilo\Promotion\Models\Promotion;

class PromotionFactory extends Factory
{
    protected $model = Promotion::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(mt_rand(2, 5), true),
        ];
    }

    public function expired(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'ends_at' => Carbon::now()->subDays(2),
            ];
        });
    }

    public function inActivePeriod(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'starts_at' => Carbon::now()->subDays(3),
                'ends_at' => Carbon::now()->addDays(3),
            ];
        });
    }
}
