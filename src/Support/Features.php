<?php

declare(strict_types=1);

/**
 * Contains the Features class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-09-07
 *
 */

namespace Vanilo\Support;

use Vanilo\Contracts\Feature;
use Vanilo\Support\Features\MultiChannel;
use Vanilo\Support\Features\Pricing;

class Features
{
    private static ?MultiChannel $multiChannel = null;

    private static ?Pricing $pricing = null;

    public static function findByName(string $name): ?Feature
    {
        return match ($name) {
            'pricing' => self::pricing(),
            'multichannel' => self::multichannel(),
            default => null,
        };
    }

    public static function multichannel(): MultiChannel
    {
        return self::$multiChannel ?: (self::$multiChannel = new MultiChannel());
    }

    public static function pricing(): Pricing
    {
        return self::$pricing ?: (self::$pricing = new Pricing());
    }

    public static function isMultiChannelEnabled(): bool
    {
        return self::multichannel()->isEnabled();
    }

    public static function isMultiChannelDisabled(): bool
    {
        return self::multichannel()->isDisabled();
    }

    public static function isPricingEnabled(): bool
    {
        return self::pricing()->isEnabled();
    }

    public static function isPricingDisabled(): bool
    {
        return self::pricing()->isDisabled();
    }
}
