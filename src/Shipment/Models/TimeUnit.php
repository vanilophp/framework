<?php

declare(strict_types=1);

namespace Vanilo\Shipment\Models;

enum TimeUnit: string
{
    case Seconds = 's';
    case Minutes = 'm';
    case Hours = 'h';
    case Days = 'd';
    case Weeks = 'w';

    public function label(): string
    {
        return match($this)
        {
            self::Seconds => __('Seconds'),
            self::Minutes => __('Minutes'),
            self::Hours => __('Hours'),
            self::Days => __('Days'),
            self::Weeks => __('Weeks'),
        };
    }

    public static function choices(): array
    {
        $result = [];
        foreach (self::cases() as $unit) {
            $result[$unit->value] = $unit->label();
        }

        return $result;
    }
}
