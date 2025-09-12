<?php

declare(strict_types=1);

/**
 * Contains the PromotionProxy class.
 *
 * @copyright   Copyright (c) 2024 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-07-09
 *
 */

namespace Vanilo\Promotion\Models;

use Illuminate\Support\Collection;
use Konekt\Concord\Proxies\ModelProxy;

/**
 * @method static null|Promotion findByCouponCode(string $couponCode)
 * @method static Collection getAvailableOnes(bool $includeCouponBasedOnes = false)
 * @method static Collection getAvailableWithoutCoupon()
 */
class PromotionProxy extends ModelProxy
{
}
