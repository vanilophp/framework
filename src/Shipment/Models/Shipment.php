<?php

declare(strict_types=1);

/**
 * Contains the Shipment class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-02-16
 *
 */

namespace Vanilo\Shipment\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Konekt\Address\Models\AddressProxy;
use Konekt\Enum\Eloquent\CastsEnums;
use Vanilo\Contracts\Address;
use Vanilo\Shipment\Contracts\Carrier;
use Vanilo\Shipment\Contracts\Shipment as ShipmentContract;
use Vanilo\Shipment\Contracts\ShipmentStatus;

/**
 * @property int               $id
 * @property string|null       $tracking_number
 * @property int               $address_id
 * @property int|null          $carrier_id
 * @property bool              $is_trackable
 * @property ShipmentStatus    $status
 * @property float|null        $weight
 * @property float|null        $width
 * @property float|null        $height
 * @property float|null        $length
 * @property string|null       $comment
 * @property array             $configuration
 *
 * @property-read Carbon       $created_at
 * @property-read Carbon       $updated_at
 * @property-read null|Address $address
 * @property-read null|Carrier $carrier
 *
 * @method static Shipment create(array $attributes)
 */
class Shipment extends Model implements ShipmentContract
{
    use CastsEnums;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected array $enums = [
       'status' => 'ShipmentStatusProxy@enumClass',
    ];

    protected $casts = [
        'configuration' => 'json',
        'is_trackable' => 'bool',
        'weight' => 'float',
        'height' => 'float',
        'width' => 'float',
        'length' => 'float',
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

    public function getCarrier(): ?Carrier
    {
        return $this->carrier;
    }

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(CarrierProxy::modelClass(), 'carrier_id', 'id');
    }

    public function status(): ShipmentStatus
    {
        return $this->status;
    }

    public function deliveryAddress(): Address
    {
        return $this->address;
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(AddressProxy::modelClass(), 'address_id', 'id');
    }
}
