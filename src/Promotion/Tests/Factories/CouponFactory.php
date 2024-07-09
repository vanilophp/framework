<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Vanilo\Promotion\Models\Coupon;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        return [
            'code' =>$this->faker->text(15),
            'promotion_id' => PromotionFactory::new()->create()->id,
        ];
    }
}
