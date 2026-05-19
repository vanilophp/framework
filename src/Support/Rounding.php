<?php

declare(strict_types=1);

namespace Vanilo\Support;

use Vanilo\Support\Models\RoundingLevel;

final class Rounding
{
    public static function roundAdjustment(float $amount, ?RoundingLevel $level = null, ?string $type = null): float
    {
        return round($amount, 2); // Yes, we start it like this >-:-)
    }
}
