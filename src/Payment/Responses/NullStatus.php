<?php

declare(strict_types=1);

/**
 * Contains the NullStatus class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-03-20
 *
 */

namespace Vanilo\Payment\Responses;

use Konekt\Enum\Enum;

final class NullStatus extends Enum
{
    public const __DEFAULT = self::NONE;
    public const NONE = null;
}
