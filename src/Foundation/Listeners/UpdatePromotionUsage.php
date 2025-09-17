<?php

declare(strict_types=1);

namespace Vanilo\Foundation\Listeners;

use Illuminate\Support\Facades\DB;
use Vanilo\Promotion\Contracts\PromotionEvent;

class UpdatePromotionUsage
{
    public function handle(PromotionEvent $event): void
    {
        DB::transaction(function () use ($event) {
            $event->getPromotion()->usage_count += 1;
            $event->getPromotion()->save();
        });
    }
}
