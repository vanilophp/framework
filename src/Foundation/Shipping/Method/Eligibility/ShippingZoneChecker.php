<?php

declare(strict_types=1);

namespace Vanilo\Foundation\Shipping\Method\Eligibility;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Konekt\Address\Models\ZoneScope;
use Konekt\Address\Query\Zones;
use Vanilo\Checkout\Contracts\Checkout;

class ShippingZoneChecker implements Checker
{
    public function onBuildQuery(Builder $shippingMethodQuery, Checkout $checkout): void
    {
        $address = $checkout->getShippingAddress();
        if (null !== $address?->getCountryCode()) {
            $zones = Zones::withScope(ZoneScope::SHIPPING)->theAddressBelongsTo($address);
            if ($zones->isNotEmpty()) {
                $shippingMethodQuery->where(fn ($q) => $q->forZones($zones)->orWhereNull('zone_id'));
            } else {
                $shippingMethodQuery->whereNull('zone_id');
            }
        }
    }

    public function filter(Collection $methods, Checkout $checkout): Collection
    {
        return $methods;
    }
}
