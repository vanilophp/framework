<?php

declare(strict_types=1);

/**
 * Contains the ProductStateProxy class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-07
 *
 */

namespace Vanilo\Product\Models;

use Konekt\Concord\Proxies\EnumProxy;

/**
 * @method static array getActiveStates()
 * @method static array getViewableStates()
 * @method static array getListableStates()
 * @method static array getBuyableStates()
 * @method static array getStatesOfScope(\Vanilo\Product\Contracts\ProductAvailabilityScope $scope)
 */
class ProductStateProxy extends EnumProxy
{
}
