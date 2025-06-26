<?php

declare(strict_types=1);

namespace Vanilo\Shipment\Models;

use Konekt\Enum\Enum;
use Vanilo\Shipment\Contracts\ShippingCategoryMatchingCondition as ShippingCategoryMatchingConditionContract;

/**
 * @property-read bool $is_unused
 * @property-read bool $is_none
 * @property-read bool $is_all
 * @property-read bool $is_at_least_one
 *
 * @method bool isUnused()
 * @method bool isNone()
 * @method bool isAll()
 * @method bool isAtLeastOne()
 *
 * @method static self UNUSED()
 * @method static self NONE()
 * @method static self ALL()
 * @method static self AT_LEAST_ONE()
 */
class ShippingCategoryMatchingCondition extends Enum implements ShippingCategoryMatchingConditionContract
{
    public const __DEFAULT = self::UNUSED;

    public const UNUSED = null;
    public const NONE = 'none';
    public const ALL = 'all';
    public const AT_LEAST_ONE = 'at_least_one';

    protected static array $labels = [];

    protected static function boot()
    {
        static::$labels = [
            self::UNUSED => __('Not used'),
            self::NONE => __('None of the products must match'),
            self::ALL => __('All of the products must to match'),
            self::AT_LEAST_ONE => __('At least one product has to match'),
        ];
    }
}
