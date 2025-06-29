<?php

declare(strict_types=1);

namespace Vanilo\Foundation\Shipping\Method\Eligibility;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Vanilo\Checkout\Contracts\Checkout;
use Vanilo\Shipment\Models\ShippingMethodProxy;

class AvailableShippingMethods
{
    /** @var Checker[] */
    protected array $checkers = [];

    public function __construct(
        private Checkout $checkout,
    ) {
        $this->addCheck(new ShippingZoneChecker());
        $this->addCheck(new ShippingCategoryChecker());
    }

    public static function forCheckout(Checkout $checkout): self
    {
        return new static($checkout);
    }

    public function addCheck(Checker $checker): self
    {
        $this->checkers[] = $checker;

        return $this;
    }

    public function withoutZoneCheck(): self
    {
        foreach ($this->checkers as $key => $checker) {
            if ($checker instanceof ShippingZoneChecker) {
                unset($this->checkers[$key]);
                break;
            }
        }

        return $this;
    }

    public function withoutCategoryCheck(): self
    {
        foreach ($this->checkers as $key => $checker) {
            if ($checker instanceof ShippingCategoryChecker) {
                unset($this->checkers[$key]);
                break;
            }
        }

        return $this;
    }

    public function get(): Collection
    {
        $query = $this->getBaseQuery();
        foreach ($this->checkers as $checker) {
            $checker->onBuildQuery($query, $this->checkout);
        }

        $result = $query->get();

        foreach ($this->checkers as $checker) {
            $result = $checker->filter($result, $this->checkout);
        }

        return $result;
    }

    protected function getBaseQuery(): Builder
    {
        return ShippingMethodProxy::actives();
    }
}
