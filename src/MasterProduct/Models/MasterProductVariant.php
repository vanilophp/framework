<?php

declare(strict_types=1);

/**
 * Contains the MasterProductVariant class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-06-13
 *
 */

namespace Vanilo\MasterProduct\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vanilo\Contracts\Dimension as DimensionContract;
use Vanilo\Contracts\Stockable;
use Vanilo\MasterProduct\Contracts\MasterProductVariant as MasterProductVariantContract;
use Vanilo\Support\Dto\Dimension;

/**
 *
 * @property int $id
 * @property \Vanilo\MasterProduct\Contracts\MasterProduct $masterProduct
 * @property string $name
 * @property string $sku
 * @property float $stock
 * @property float|null $backorder
 * @property float|null $price
 * @property float|null $original_price
 * @property string|null $excerpt
 * @property float|null $weight
 * @property float|null $width
 * @property float|null $height
 * @property float|null $length
 * @property int|null $units_sold
 * @property null|Carbon $last_sale_at
 * @property null|Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static MasterProductVariant create(array $attributes = [])
 */
class MasterProductVariant extends Model implements MasterProductVariantContract, Stockable
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'price' => 'float',
        'original_price' => 'float',
        'weight' => 'float',
        'height' => 'float',
        'width' => 'float',
        'length' => 'float',
        'stock' => 'float',
    ];

    public static function findBySku(string $sku): ?MasterProductVariantContract
    {
        return static::where('sku', $sku)->first();
    }

    public function masterProduct(): BelongsTo
    {
        return $this->belongsTo(MasterProductProxy::modelClass(), 'master_product_id', 'id');
    }

    public function isOnStock(): bool
    {
        return $this->stock > 0;
    }

    public function onStockQuantity(): float
    {
        return (float) $this->stock;
    }

    public function isBackorderUnrestricted(): bool
    {
        return null === $this->backorder;
    }

    public function backorderQuantity(): ?float
    {
        return $this->backorder;
    }

    public function totalAvailableQuantity(): float
    {
        return $this->stock + (float) $this->backorder;
    }

    public function hasDimensions(): bool
    {
        return null !== $this->width && null !== $this->height && null !== $this->length;
    }

    public function dimension(): ?DimensionContract
    {
        if (!$this->hasDimensions()) {
            return null;
        }

        return new Dimension($this->width, $this->height, $this->length);
    }

    public function hasOwnName(): bool
    {
        return null !== $this->getRawOriginal('name');
    }

    public function hasOwnPrice(): bool
    {
        return null !== $this->getRawOriginal('price');
    }

    public function hasOwnOriginalPrice(): bool
    {
        return null !== $this->getRawOriginal('original_price');
    }

    public function hasOwnExcerpt(): bool
    {
        return null !== $this->getRawOriginal('excerpt');
    }

    public function hasOwnHeight(): bool
    {
        return null !== $this->getRawOriginal('height');
    }

    public function hasOwnWidth(): bool
    {
        return null !== $this->getRawOriginal('width');
    }

    public function hasOwnLength(): bool
    {
        return null !== $this->getRawOriginal('length');
    }

    public function hasOwnWeight(): bool
    {
        return null !== $this->getRawOriginal('weight');
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => is_null($value) ? $this->masterProduct?->name : $value,
        );
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => is_null($value) ? $this->masterProduct?->price : floatval($value),
        );
    }

    protected function originalPrice(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => is_null($value) ? $this->masterProduct?->original_price : floatval($value),
        );
    }

    protected function excerpt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => is_null($value) ? $this->masterProduct?->excerpt : $value,
        );
    }

    protected function height(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => is_null($value) ? $this->masterProduct?->height : floatval($value),
        );
    }

    protected function width(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => is_null($value) ? $this->masterProduct?->width : floatval($value),
        );
    }

    protected function length(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => is_null($value) ? $this->masterProduct?->length : floatval($value),
        );
    }

    protected function weight(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => is_null($value) ? $this->masterProduct?->weight : floatval($value),
        );
    }
}
