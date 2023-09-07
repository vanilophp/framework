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

namespace Vanilo\Foundation;

use Vanilo\Foundation\Features\MultiChannel;

class Features
{
    private static ?MultiChannel $multiChannel = null;

    public static function multichannel(): MultiChannel
    {
        return self::$multiChannel ?: (self::$multiChannel = new MultiChannel());
    }

    public static function isMultiChannelEnabled(): bool
    {
        return self::multichannel()->isEnabled();
    }

    public static function isMultiChannelDisabled(): bool
    {
        return self::multichannel()->isDisabled();
    }
}
