<?php

declare(strict_types=1);

namespace Vanilo\Shipment\Contracts;

use Konekt\Enum\EnumInterface;

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
interface ShippingCategoryMatchingCondition extends EnumInterface
{
}
