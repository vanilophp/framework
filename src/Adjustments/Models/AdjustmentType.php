<?php

declare(strict_types=1);

/**
 * Contains the AdjustmentType class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-05-27
 *
 */

namespace Vanilo\Adjustments\Models;

use Konekt\Enum\Enum;
use Vanilo\Adjustments\Contracts\AdjustmentType as AdjustmentTypeContract;

/**
 * @method static AdjustmentType PROMOTION()
 * @method static AdjustmentType SHIPPING()
 * @method static AdjustmentType TAX()
 * @method static AdjustmentType MISC()
 *
 * @method bool isPromotion()
 * @method bool isShipping()
 * @method bool isTax()
 * @method bool isMisc()
 */
class AdjustmentType extends Enum implements AdjustmentTypeContract
{
    public const PROMOTION = 'promotion';
    public const SHIPPING = 'shipping';
    public const TAX = 'tax';
    public const MISC = 'misc';

    protected static array $labels = [];

    protected static function boot()
    {
        static::$labels = [
            self::PROMOTION => __('Promotion'),
            self::SHIPPING => __('Shipping Fee'),
            self::TAX => __('Taxes'),
            self::MISC => __('Other'),
        ];
    }
}
