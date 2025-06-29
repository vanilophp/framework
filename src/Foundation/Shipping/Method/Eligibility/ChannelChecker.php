<?php

declare(strict_types=1);

namespace Vanilo\Foundation\Shipping\Method\Eligibility;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Vanilo\Channel\Contracts\Channel;
use Vanilo\Checkout\Contracts\Checkout;

class ChannelChecker implements Checker
{
    public function __construct(
        private ?Channel $channel
    ) {
    }

    public function onBuildQuery(Builder $shippingMethodQuery, Checkout $checkout): void
    {
        if (null !== $this->channel) {
            $shippingMethodQuery->withinChannels($this->channel);
        }
    }

    public function filter(Collection $methods, Checkout $checkout): Collection
    {
        return $methods;
    }
}
