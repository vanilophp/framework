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
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Konekt\Address\Concerns\Zoneable;
use Konekt\Address\Contracts\Zone;
use Konekt\Enum\Eloquent\CastsEnums;
use Vanilo\Contracts\Schematized;
use Vanilo\Shipment\Calculators\NullShippingFeeCalculator;
use Vanilo\Shipment\Contracts\ShippingCategory;
use Vanilo\Shipment\Contracts\ShippingCategoryMatchingCondition;
use Vanilo\Shipment\Contracts\ShippingFeeCalculator;
use Vanilo\Shipment\Contracts\ShippingMethod as ShippingMethodContract;
use Vanilo\Shipment\ShippingFeeCalculators;
use Vanilo\Shipment\Traits\BelongsToCarrier;
use Vanilo\Support\Dto\SchemaDefinition;
use Vanilo\Support\Traits\ConfigurableModel;

/**
 * @property int $id
 * @property string $name
 * @property string|null $calculator
 * @property int|null $carrier_id
 * @property int|null $zone_id
 * @property boolean $is_active
 * @property int|null $eta_min
 * @property int|null $eta_max
 * @property string|null $eta_units
 * @property array $configuration
 * @property int|null $shipping_category_id
 * @property ShippingCategoryMatchingCondition $shipping_category_matching_condition
 *
 * @method static Builder actives()
 * @method static Builder inactives()
 *
 * @method static ShippingMethod create(array $attributes)
 */
class ShippingMethod extends Model implements ShippingMethodContract
{
    use BelongsToCarrier;
    use ConfigurableModel;
    use Zoneable;
    use CastsEnums;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected array $enums = [
        'shipping_category_matching_condition' => 'ShippingCategoryMatchingConditionProxy@enumClass',
    ];

    protected $casts = [
        'configuration' => 'json',
        'is_active' => 'bool',
    ];

    public static function availableOnesForZone(Zone|int $zone): Collection
    {
        return self::forZone($zone)->actives()->get();
    }

    public static function availableOnesForZones(Zone|int ...$zones): Collection
    {
        return self::forZones(array_map(fn (Zone|int $zone) => is_int($zone) ? $zone : $zone->id, $zones))
            ->actives()
            ->get();
    }

    public function getCalculator(): ShippingFeeCalculator
    {
        if (null === $this->calculator) {
            return new NullShippingFeeCalculator();
        }

        return ShippingFeeCalculators::make($this->calculator);
    }

    public function estimate(?object $subject = null): ShippingFee
    {
        return $this->getCalculator()->calculate($subject, $this->configuration());
    }

    public function getConfigurationSchema(): ?Schematized
    {
        return SchemaDefinition::wrap($this->getCalculator());
    }

    public function hasShippingCategory(): bool
    {
        return !is_null($this->shipping_category_id);
    }

    public function getShippingCategory(): ?ShippingCategory
    {
        return $this->shippingCategory;
    }

    public function getShippingCategoryMatchingCondition(): ?ShippingCategoryMatchingCondition
    {
        return $this->shipping_category_matching_condition;
    }

    public function shippingCategory(): BelongsTo
    {
        return $this->belongsTo(ShippingCategoryProxy::modelClass(), 'shipping_category_id', 'id');
    }

    public function scopeActives(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeInactives(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }
}
