<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Vanilo\Promotion\Models\Coupon;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        return [
            'code' => Str::ulid()->toBase58(),
            'promotion_id' => PromotionFactory::new()->create()->id,
        ];
    }
}
