<?php

declare(strict_types=1);

namespace Vanilo\Foundation\Shipping\Method\Eligibility;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Vanilo\Channel\Contracts\Channel;
use Vanilo\Checkout\Contracts\Checkout;

class ChannelChecker implements Checker
{
    private array $channels;
    public function __construct(Channel ...$channels)
    {
        $this->channels = $channels;
    }

    public function onBuildQuery(Builder $shippingMethodQuery, Checkout $checkout): void
    {
        if (!empty($this->channels)) {
            $shippingMethodQuery->withinChannels($this->channels);
        }
    }

    public function filter(Collection $methods, Checkout $checkout): Collection
    {
        return $methods;
    }
}
