<?php

declare(strict_types=1);

/**
 * Contains the Configuration interface.
 *
 * @copyright   Copyright (c) 2023 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-01-10
 *
 */

namespace Vanilo\Contracts;

use ArrayAccess;
use Countable;

interface Configuration extends ArrayAccess, Countable
{
}
