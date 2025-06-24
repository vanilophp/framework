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

/**
 * @property bool $is_draft
 * @property bool $is_inactive
 * @property bool $is_unlisted
 * @property bool $is_active
 * @property bool $is_unavailable
 * @property bool $is_retired
 *
 * @method static ProductState DRAFT()
 * @method static ProductState INACTIVE()
 * @method static ProductState UNLISTED()
 * @method static ProductState ACTIVE()
 * @method static ProductState UNAVAILABLE()
 * @method static ProductState RETIRED()
 */
class ProductState extends Enum implements ProductStateContract
{
    public const __DEFAULT = self::DRAFT;

    public const DRAFT = 'draft';
    public const INACTIVE = 'inactive';
    public const UNLISTED = 'unlisted';
    public const ACTIVE = 'active';
    public const UNAVAILABLE = 'unavailable';
    public const RETIRED = 'retired';

    protected static array $activeStates = [self::ACTIVE, self::UNLISTED];

    protected static array $viewableStates = [self::ACTIVE, self::UNLISTED, self::UNAVAILABLE, self::RETIRED];

    protected static array $listableStates = [self::ACTIVE, self::UNAVAILABLE];

    protected static array $buyableStates = [self::ACTIVE, self::UNLISTED];

    public function isActive(): bool
    {
        return in_array($this->value, static::$activeStates);
    }

    public function isViewable(): bool
    {
        return in_array($this->value, static::$viewableStates);
    }

    public function isListable(): bool
    {
        return in_array($this->value, static::$listableStates);
    }

    public function isBuyable(): bool
    {
        return in_array($this->value, static::$buyableStates);
    }

    public static function getViewableStates(): array
    {
        return static::$viewableStates;
    }

    public static function getListableStates(): array
    {
        return static::$listableStates;
    }

    public static function getBuyableStates(): array
    {
        return static::$buyableStates;
    }

    public static function getActiveStates(): array
    {
        return static::$activeStates;
    }
}
