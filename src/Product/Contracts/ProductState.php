<?php

declare(strict_types=1);

/**
 * Contains the ProductState enum interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-07
 *
 */

namespace Vanilo\Product\Contracts;

interface ProductState
{
    /**
     * @deprecated Use the more granular isViewable(), isListable() and isBuyable() methods instead
     */
    public function isActive(): bool;

    public function isViewable(): bool;

    public function isListable(): bool;

    public function isBuyable(): bool;

    public function isInScope(ProductAvailabilityScope $scope): bool;

    /**
     * @deprecated use the more granular getViewableStates(), getListableStates() and getBuyableStates() methods instead
     */
    public static function getActiveStates(): array;

    public static function getViewableStates(): array;

    public static function getListableStates(): array;

    public static function getBuyableStates(): array;

    public static function getStatesOfScope(ProductAvailabilityScope $scope): array;
}
