<?php

declare(strict_types=1);

/**
 * Contains the Promotion interface.
 *
 * @copyright   Copyright (c) 2024 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-07-09
 *
 */

namespace Vanilo\Promotion\Contracts;

use Illuminate\Support\Collection;
use Vanilo\Promotion\Models\PromotionStatus;

interface Promotion
{
    public static function findByCouponCode(string $couponCode): ?Promotion;

    public function isValid(?\DateTimeInterface $at = null): bool;

    public function hasStarted(?\DateTimeInterface $at = null): bool;

    public function isExpired(?\DateTimeInterface $at = null): bool;

    public function isDepleted(): bool;

    /** A read-only, calculated enum */
    public function getStatus(): PromotionStatus;

    public function isEligible(object $subject): bool;

    public function isCouponBased(): bool;

    public function getCoupons(): Collection;

    /** @return Collection|PromotionRule[] */
    public function getRules(): Collection;

    /** @return Collection|PromotionAction[] */
    public function getActions(): Collection;

    public function addRule(PromotionRuleType|string $type, array $configuration): self;

    public function addAction(PromotionActionType|string $type, array $configuration): self;
}
