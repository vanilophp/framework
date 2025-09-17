<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Contracts;

interface PromotionEvent
{
    public function getPromotion(): Promotion;
}
