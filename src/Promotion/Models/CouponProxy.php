<?php

declare(strict_types=1);

/**
 * Contains the CouponProxy class.
 *
 * @copyright   Copyright (c) 2024 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-07-09
 *
 */

namespace Vanilo\Promotion\Models;

use Konekt\Concord\Proxies\ModelProxy;
use Vanilo\Promotion\Contracts\Coupon;

/**
 * @method static null|Coupon findByCode(string $code)
 */
class CouponProxy extends ModelProxy
{
}
