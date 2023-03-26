<?php

declare(strict_types=1);

/**
 * Contains the TaxRate class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-26
 *
 */

namespace Vanilo\Taxes\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Konekt\Address\Contracts\Zone;
use Konekt\Address\Models\ZoneProxy;
use Vanilo\Support\Traits\ConfigurableModel;
use Vanilo\Taxes\Calculators\NullTaxCalculator;
use Vanilo\Taxes\Contracts\TaxCalculator;
use Vanilo\Taxes\Contracts\TaxCategory;
use Vanilo\Taxes\Contracts\TaxRate as TaxRateContract;
use Vanilo\Taxes\TaxCalculators;
use Vanilo\Taxes\Traits\BelongsToTaxCategory;

/**
 * @property-read int $id
 * @property string $name
 * @property integer|null $tax_category_id
 * @property integer|null $zone_id
 * @property float $rate
 * @property string $calculator
 * @property array|null $configuration
 * @property boolean $is_active
 * @property Carbon|null $valid_from
 * @property Carbon|null $valid_until
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read TaxCategory|null $taxCategory
 * @property-read Zone|null $zone
 *
 * @method static Builder byTaxCategory(TaxCategory|int $taxCategory)
 * @method Builder forZone(Zone|int $zone)
 * @method Builder forZones(array|Collection $zones)
 * @method Builder actives()
 * @method Builder inactives()
 */
class TaxRate extends Model implements TaxRateContract
{
    use BelongsToTaxCategory;
    use ConfigurableModel;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'rate' => 'float',
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

    public function getName(): string
    {
        return $this->name;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function getTaxCategory(): ?TaxCategory
    {
        return $this->taxCategory;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function getCalculator(): TaxCalculator
    {
        if (null === $this->calculator) {
            return new NullTaxCalculator();
        }

        return TaxCalculators::make($this->calculator);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(ZoneProxy::modelClass(), 'zone_id', 'id');
    }

    public function scopeActives(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeInactives(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    public function scopeForZone(Builder $query, Zone|int $zone): Builder
    {
        return $query->where('zone_id', is_int($zone) ? $zone : $zone->id);
    }

    public function scopeForZones(Builder $query, array|Collection $zones): Builder
    {
        if (is_array($zones)) {
            $zones = array_map(fn (Zone|int $zone) => is_int($zone) ? $zone : $zone->id, $zones);
        } else {
            $zones = $zones->map(fn (Zone $zone) => $zone->id);
        }

        return $query->whereIn('zone_id', $zones);
    }

    public function scopeByTaxCategory(Builder $query, TaxCategory|int $taxCategory): Builder
    {
        return $query->where('tax_category_id', is_int($taxCategory) ? $taxCategory : $taxCategory->id);
    }
}
