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

interface Carrier
{
    public function name(): string;
}
