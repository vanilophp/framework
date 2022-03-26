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

use Illuminate\Database\Eloquent\Model;
use Vanilo\Shipment\Traits\BelongsToCarrier;

/**
 * @property int $id
 * @property string $name
 * @property int|null $carrier_id
 * @property array $configuration
 */
class ShippingMethod extends Model
{
    use BelongsToCarrier;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'configuration' => 'json',
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (null === $model->configuration) {
                $model->configuration = [];
            }
        });
    }
}
