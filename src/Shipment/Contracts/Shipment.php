<?php

declare(strict_types=1);

/**
 * Contains the Shipment interface.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-02-16
 *
 */

namespace Vanilo\Shipment\Contracts;

use Vanilo\Contracts\Address;

interface Shipment
{
    public function getAddress(): Address;
}
