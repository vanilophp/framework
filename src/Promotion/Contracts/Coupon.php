<?php

declare(strict_types=1);

/**
 * Contains the Coupon interface.
 *
 * @copyright   Copyright (c) 2024 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-07-09
 *
 */

namespace Vanilo\Promotion\Contracts;

interface Coupon
{
    public static function findByCode(string $code): ?Coupon;

    public function getPromotion(): Promotion;

    public function canBeUsed(): bool;

    public function isExpired(): bool;

    public function isDepleted(): bool;
}
