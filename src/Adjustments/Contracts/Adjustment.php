<?php

declare(strict_types=1);

/**
 * Contains the Adjustment interface.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-05-27
 *
 */

namespace Vanilo\Adjustments\Contracts;

interface Adjustment
{
    public function getType(): AdjustmentType;

    public function getAdjustable(): Adjustable;

    public function getAdjuster(): Adjuster;

    public function getOrigin(): ?string;

    public function getTitle(): string;

    public function getDescription(): ?string;

    public function getAmount(): float;

    public function setAmount(float $amount): void;

    public function getData(string $key = null);

    /**
     * Adjustments that increase the total are called "charges".
     */
    public function isCharge(): bool;

    /**
     * Adjustments that decrease the total are called "credits".
     */
    public function isCredit(): bool;

    public function isIncluded(): bool;

    public function isLocked(): bool;

    public function lock(): void;

    public function unlock(): void;
}
