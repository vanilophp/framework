<?php

declare(strict_types=1);

namespace Vanilo\Shipment\Models;

use Konekt\Enum\Enum;
use Vanilo\Shipment\Contracts\ShippingCategoryMatchingCondition as ShippingCategoryMatchingConditionContract;

/**
 * @property-read bool $is_none
 * @property-read bool $is_all
 * @property-read bool $is_at_least_one
 *
 * @method bool isNone()
 * @method bool isAll()
 * @method bool isAtLeastOne()
 *
 * @method static self NONE()
 * @method static self ALL()
 * @method static self AT_LEAST_ONE()
 */
class ShippingCategoryMatchingCondition extends Enum implements ShippingCategoryMatchingConditionContract
{
    public const __DEFAULT = self::NONE;

    public const NONE = 'none';
    public const ALL = 'all';
    public const AT_LEAST_ONE = 'at_least_one';
}
