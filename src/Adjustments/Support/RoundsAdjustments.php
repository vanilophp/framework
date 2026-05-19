<?php

declare(strict_types=1);

namespace Vanilo\Adjustments\Support;

use Vanilo\Adjustments\Contracts\AdjustmentType;
use Vanilo\Adjustments\Models\AdjustmentTypeProxy;
use Vanilo\Support\Models\RoundingLevel;
use Vanilo\Support\Rounding;

trait RoundsAdjustments
{
    protected function round(float $value, ?RoundingLevel $level = null, ?AdjustmentType $type = null): float
    {
        return Rounding::roundAdjustment($value, $level, $type->value());
    }

    protected function roundTax(float $value, ?RoundingLevel $level = null): float
    {
        return $this->round($value, $level, AdjustmentTypeProxy::TAX());
    }

    protected function roundPromotion(float $value, ?RoundingLevel $level = null): float
    {
        return $this->round($value, $level, AdjustmentTypeProxy::PROMOTION());
    }
}
