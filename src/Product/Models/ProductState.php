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
use Vanilo\Product\Contracts\ProductAvailabilityScope as ProductAvailabilityScopeContract;
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

    protected static array $scopeStates = [
        'active' => [self::ACTIVE, self::UNLISTED],
        ProductAvailabilityScope::LISTING => [self::ACTIVE, self::UNAVAILABLE],
        ProductAvailabilityScope::VIEWING => [self::ACTIVE, self::UNLISTED, self::UNAVAILABLE, self::RETIRED],
        ProductAvailabilityScope::BUYING => [self::ACTIVE, self::UNLISTED],
    ];

    public function isActive(): bool
    {
        return in_array($this->value, static::$scopeStates['active']);
    }

    public function isViewable(): bool
    {
        return $this->isInScope(ProductAvailabilityScope::VIEWING());
    }

    public function isListable(): bool
    {
        return $this->isInScope(ProductAvailabilityScope::LISTING());
    }

    public function isBuyable(): bool
    {
        return $this->isInScope(ProductAvailabilityScope::BUYING());
    }

    public function isInScope(ProductAvailabilityScopeContract $scope): bool
    {
        return in_array($this->value, static::$scopeStates[$scope->value()]);
    }

    public static function getViewableStates(): array
    {
        return static::getStatesOfScope(ProductAvailabilityScope::VIEWING());
    }

    public static function getListableStates(): array
    {
        return static::getStatesOfScope(ProductAvailabilityScope::LISTING());
    }

    public static function getBuyableStates(): array
    {
        return static::getStatesOfScope(ProductAvailabilityScope::BUYING());
    }

    public static function getActiveStates(): array
    {
        return static::$scopeStates['active'];
    }

    public static function getStatesOfScope(ProductAvailabilityScopeContract $scope): array
    {
        return static::$scopeStates[$scope->value()] ?? [];
    }
}
