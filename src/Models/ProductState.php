<?php
/**
 * Contains the ProductState enum class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-07
 *
 */

namespace Vanilo\Product\Models;

use Konekt\Enum\Enum;
use Vanilo\Product\Contracts\ProductState as ProductStateContract;

class ProductState extends Enum implements ProductStateContract
{
    const __DEFAULT = self::DRAFT;

    const DRAFT       = 'draft';
    const INACTIVE    = 'inactive';
    const ACTIVE      = 'active';
    const UNAVAILABLE = 'unavailable';
    const RETIRED     = 'retired';

    protected static $activeStates = [self::ACTIVE];

    /**
     * @inheritdoc
     */
    public function isActive(): bool
    {
        return in_array($this->value, static::$activeStates);
    }

    /**
     * @inheritdoc
     */
    public static function getActiveStates(): array
    {
        return static::$activeStates;
    }
}
