<?php

declare(strict_types=1);

/**
 * Contains the TaxRate interface.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-26
 *
 */

namespace Vanilo\Taxes\Contracts;

use Konekt\Address\Contracts\Zone;
use Vanilo\Contracts\Configurable;

interface TaxRate extends Configurable
{
    public static function findOneForZone(Zone|int $zone, bool $activesOnly = true): ?TaxRate;

    public function getName(): string;

    public function getCalculator(): TaxCalculator;

    public function getZone(): ?Zone;

    public function getTaxCategory(): ?TaxCategory;

    public function getRate(): float;
}
