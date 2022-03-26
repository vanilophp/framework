<?php

declare(strict_types=1);

/**
 * Contains the BelongsToCarrier trait.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-03-26
 *
 */

namespace Vanilo\Shipment\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vanilo\Shipment\Contracts\Carrier;
use Vanilo\Shipment\Models\CarrierProxy;

/**
 * @property-read null|Carrier $carrier
 */
trait BelongsToCarrier
{
    public function getCarrier(): ?Carrier
    {
        return $this->carrier;
    }

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(CarrierProxy::modelClass(), 'carrier_id', 'id');
    }
}
