<?php

declare(strict_types=1);

/**
 * Contains the Carrier interface.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-28
 *
 */

namespace Vanilo\Shipment\Contracts;

use Vanilo\Contracts\Configurable;

interface Carrier extends Configurable
{
    /** @deprecated Use getName() instead. This method will be removed in v6.0 */
    public function name(): string;

    public function getName(): string;
}
