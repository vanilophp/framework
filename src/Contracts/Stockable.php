<?php

declare(strict_types=1);

/**
 * Contains the Stockable interface.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-11-23
 *
 */

namespace Vanilo\Contracts;

interface Stockable
{
    public function isOnStock(): bool;

    public function onStockQuantity(): float;

    public function isBackorderUnrestricted(): bool;

    public function backorderQuantity(): ?float;

    public function totalAvailableQuantity(): float;
}
