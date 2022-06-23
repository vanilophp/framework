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
use Vanilo\MasterProduct\Contracts\MasterProductVariant as MasterProductVariantContract;

/**
 *
 * @property int $id
 * @property string $name
 * @property string $sku
 * @property float $stock
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
class MasterProductVariant extends Model implements MasterProductVariantContract
{
    protected $table = 'master_product_variants';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'price' => 'float',
        'original_price' => 'float',
        'weight' => 'float',
        'height' => 'float',
        'width' => 'float',
        'length' => 'float',
    ];

    public function masterProduct(): BelongsTo
    {
        return $this->belongsTo(MasterProductProxy::modelClass(), 'master_product_id', 'id');
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
            get: fn ($value) => is_null($value) ? $this->masterProduct?->price : $value,
        );
    }

    protected function originalPrice(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => is_null($value) ? $this->masterProduct?->original_price : $value,
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
            get: fn ($value) => is_null($value) ? $this->masterProduct?->height : $value,
        );
    }

    protected function width(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => is_null($value) ? $this->masterProduct?->width : $value,
        );
    }

    protected function length(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => is_null($value) ? $this->masterProduct?->length : $value,
        );
    }

    protected function weight(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => is_null($value) ? $this->masterProduct?->weight : $value,
        );
    }
}
