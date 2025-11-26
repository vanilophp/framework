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

use Vanilo\Contracts\DetailedAmount;
use Vanilo\Contracts\Schematized;

interface TaxCalculator extends Schematized
{
    public static function getName(): string;

    /**
     * We don't set the return type on the language level
     * since the adjustments module is optional
     * @todo Remove this nonsense in v6 and add the proper return type
     *
     * @return null|\Vanilo\Adjustments\Contracts\Adjuster
     */
    public function getAdjuster(?array $configuration = null): ?object;

    /**
     * @todo change ?object to Adjustable in v6. Also - when can the configuration be null?
     */
    public function calculate(?object $subject = null, ?array $configuration = null): DetailedAmount;
}
