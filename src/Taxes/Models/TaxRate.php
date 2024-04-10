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
use Konekt\Address\Concerns\Zoneable;
use Konekt\Address\Contracts\Zone;
use Konekt\Address\Models\ZoneProxy;
use Vanilo\Contracts\Schematized;
use Vanilo\Support\Dto\SchemaDefinition;
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
 *
 * @method static Builder byTaxCategory(TaxCategory|int $taxCategory)
 * @method Builder actives()
 * @method Builder inactives()
 */
class TaxRate extends Model implements TaxRateContract
{
    use BelongsToTaxCategory;
    use ConfigurableModel;
    use Zoneable;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'rate' => 'float',
        'configuration' => 'json',
        'is_active' => 'bool',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function findOneByZoneAndCategory(Zone|int $zone, TaxCategory|int $taxCategory, bool $activesOnly = true): ?TaxRateContract
    {
        $query = self::forZone($zone)->byTaxCategory($taxCategory);
        if ($activesOnly) {
            $query->actives();
        }

        return $query->first();
    }

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

    public function getConfigurationSchema(): ?Schematized
    {
        return SchemaDefinition::wrap($this->getCalculator());
    }

    public function scopeActives(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeInactives(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    public function scopeByTaxCategory(Builder $query, TaxCategory|int $taxCategory): Builder
    {
        return $query->where('tax_category_id', is_int($taxCategory) ? $taxCategory : $taxCategory->id);
    }
}
