<?php

declare(strict_types=1);

/**
 * Contains the TaxCalculator interface.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-26
 *
 */

namespace Vanilo\Taxes\Contracts;

use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Adjustments\Contracts\Adjuster;
use Vanilo\Contracts\DetailedAmount;
use Vanilo\Contracts\Schematized;

interface TaxCalculator extends Schematized
{
    public static function getName(): string;

    public function getAdjuster(?array $configuration = null): ?Adjuster;

    public function calculate(Adjustable $subject, ?array $configuration = null): DetailedAmount;
}
