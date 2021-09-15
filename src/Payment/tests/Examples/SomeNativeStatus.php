<?php

declare(strict_types=1);

/**
 * Contains the SomeNativeStatus class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-03-21
 *
 */

namespace Vanilo\Payment\Tests\Examples;

use Konekt\Enum\Enum;

/**
 * @method static SomeNativeStatus CAPTURED()
 * @method static SomeNativeStatus REJECTED()
 */
class SomeNativeStatus extends Enum
{
    public const CAPTURED = 'captured';
    public const REJECTED = 'rejected';
}
