<?php

declare(strict_types=1);

/**
 * Contains the TaxRateProxy class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-26
 *
 */

namespace Vanilo\Taxes\Models;

use Konekt\Concord\Proxies\ModelProxy;

/**
 * @method static \Vanilo\Taxes\Contracts\TaxRate|null findOneForZone(\Konekt\Address\Contracts\Zone|int $zone, bool $activesOnly = true)
 */
class TaxRateProxy extends ModelProxy
{
}
