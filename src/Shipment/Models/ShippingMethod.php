<?php

declare(strict_types=1);

/**
 * Contains the ShippingMethod class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-03-26
 *
 */

namespace Vanilo\Shipment\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Vanilo\Shipment\Contracts\ShippingMethod as ShippingMethodContract;
use Vanilo\Shipment\Traits\BelongsToCarrier;
use Vanilo\Support\Traits\ConfigurableModel;

/**
 * @property int $id
 * @property string $name
 * @property int|null $carrier_id
 * @property boolean $is_active
 * @property array $configuration
 *
 * @method static Builder actives()
 * @method static Builder inactives()
 *
 * @method static Carrier create(array $attributes)
 */
class ShippingMethod extends Model implements ShippingMethodContract
{
    use BelongsToCarrier;
    use ConfigurableModel;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'configuration' => 'json',
        'is_active' => 'bool',
    ];

    public function scopeActives(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeInactives(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }
}
