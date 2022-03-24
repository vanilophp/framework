<?php

declare(strict_types=1);

/**
 * Contains the RecalculatesAdjustments trait.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-07-26
 *
 */

namespace Vanilo\Adjustments\Support;

use Vanilo\Adjustments\Contracts\Adjustment;
use Vanilo\Adjustments\Contracts\AdjustmentCollection;

/**
 * Only apply this trait on an adjustable!
 */
trait RecalculatesAdjustments
{
    public function recalculateAdjustments(): void
    {
        /** @var Adjustment $adjustment */
        foreach ($this->adjustments() as $adjustment) {
            $adjustment->getAdjuster()->recalculate($adjustment, $this);
        }
    }
}
