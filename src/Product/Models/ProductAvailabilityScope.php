<?php

declare(strict_types=1);

namespace Vanilo\Product\Models;

use Konekt\Enum\Enum;
use Vanilo\Product\Contracts\ProductAvailabilityScope as ProductAvailabilityScopeContract;

/**
 * @method static ProductAvailabilityScope LISTING()
 * @method static ProductAvailabilityScope VIEWING()
 * @method static ProductAvailabilityScope BUYING()
 *
 * @method bool isListing()
 * @method bool isViewing()
 * @method bool isBuying()
 *
 * @property-read bool $is_listing
 * @property-read bool $is_viewing
 * @property-read bool $is_buying
 */
class ProductAvailabilityScope extends Enum implements ProductAvailabilityScopeContract
{
    /** Represent states that allow a product to be listed publicly */
    public const string LISTING = 'listing';

    /** Represent states that allow a product to be viewed individually */
    public const string VIEWING = 'viewing';

    /** Represent states that allow a product to be bought */
    public const string BUYING = 'buying';

    public function getStates(): array
    {
        return ProductStateProxy::getStatesOfScope($this);
    }
}
