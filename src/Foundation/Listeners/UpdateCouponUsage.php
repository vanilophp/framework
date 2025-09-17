<?php

declare(strict_types=1);

namespace Vanilo\Foundation\Listeners;

use Illuminate\Support\Facades\DB;
use Vanilo\Checkout\Contracts\CouponEvent;
use Vanilo\Promotion\Models\CouponProxy;

class UpdateCouponUsage
{
    public function handle(CouponEvent $event): void
    {
        if (null !== $coupon = CouponProxy::findByCode($event->getCouponCode())) {
            DB::transaction(function () use ($coupon) {
                $coupon->usage_count += 1;
                $coupon->save();
            });
        }
    }
}
