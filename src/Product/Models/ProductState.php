<?php

declare(strict_types=1);
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
    public const __DEFAULT = self::DRAFT;

    public const DRAFT = 'draft';
    public const INACTIVE = 'inactive';
    public const ACTIVE = 'active';
    public const UNAVAILABLE = 'unavailable';
    public const RETIRED = 'retired';

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
