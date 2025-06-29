<?php

declare(strict_types=1);

namespace Vanilo\Foundation\Shipping\Method\Eligibility;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Vanilo\Checkout\Contracts\Checkout;

interface Checker
{
    public function onBuildQuery(Builder $shippingMethodQuery, Checkout $checkout): void;

    public function filter(Collection $methods, Checkout $checkout): Collection;
}
