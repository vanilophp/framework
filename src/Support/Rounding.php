<?php

declare(strict_types=1);

namespace Vanilo\Support;

use Vanilo\Support\Models\RoundingLevel;
use Vanilo\Support\Models\RoundingTarget;

final class Rounding
{
    private const int FALLBACK = 2;

    private static ?self $_instance = null;

    private array $ruleset = [
        RoundingTarget::Any->value => [
            RoundingLevel::Any->value => 2,
        ],
    ];

    public static function roundAdjustment(float $amount, RoundingLevel $level = RoundingLevel::Any, ?string $type = null): float
    {
        return round($amount, self::getRoundingRuleFor(RoundingTarget::Adjustment, $level));
    }

    public static function getRoundingRuleFor(RoundingTarget $target, RoundingLevel $level = RoundingLevel::Any): int
    {
        $rules = self::instance()->ruleset;

        if (isset($rules[$target->value][$level->value])) {
            return $rules[$target->value][$level->value];
        }

        if (isset($rules[$target->value][RoundingLevel::Any->value])) {
            return $rules[$target->value][RoundingLevel::Any->value];
        }

        if (isset($rules[RoundingTarget::Any->value][$level->value])) {
            return $rules[RoundingTarget::Any->value][$level->value];
        }

        if (isset($rules[RoundingTarget::Any->value][RoundingLevel::Any->value])) {
            return $rules[RoundingTarget::Any->value][RoundingLevel::Any->value];
        }

        return self::FALLBACK;
    }

    public static function setRoundingRuleFor(RoundingTarget $target, RoundingLevel $level, int $precision): void
    {
        self::instance()->ruleset[$target->value][$level->value] = $precision;
    }

    private static function instance(): self
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
