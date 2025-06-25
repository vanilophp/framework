<?php

declare(strict_types=1);

namespace Vanilo\Product\Models;

use Konekt\Concord\Proxies\EnumProxy;

/**
 * @method static \Vanilo\Product\Contracts\ProductAvailabilityScope LISTING()
 * @method static \Vanilo\Product\Contracts\ProductAvailabilityScope VIEWING()
 * @method static \Vanilo\Product\Contracts\ProductAvailabilityScope BUYING()
 */
class ProductAvailabilityScopeProxy extends EnumProxy
{
}
