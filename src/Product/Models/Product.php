<?php

declare(strict_types=1);

/**
 * Contains the Product class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-07
 *
 */

namespace Vanilo\Product\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Konekt\Enum\Eloquent\CastsEnums;
use Vanilo\Contracts\Dimension;
use Vanilo\Product\Contracts\Product as ProductContract;

/**
 * @property int $id
 * @property string $name
 * @property string|null $slug
 * @property string $sku
 * @property float|null $price
 * @property float|null $original_price
 * @property string|null $excerpt
 * @property string|null $description
 * @property ProductState $state
 * @property float|null $weight
 * @property float|null $width
 * @property float|null $height
 * @property float|null $length
 * @property string|null $ext_title
 * @property string|null $meta_keywords
 * @property string|null $meta_description
 * @property null|Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static Product create(array $attributes)
 */
class Product extends Model implements ProductContract
{
    use CastsEnums;
    use Sluggable;
    use SluggableScopeHelpers;

    protected $table = 'products';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'price' => 'float',
        'original_price' => 'float',
        'weight' => 'float',
        'height' => 'float',
        'width' => 'float',
        'length' => 'float',
    ];

    protected $enums = [
        'state' => 'ProductStateProxy@enumClass'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function isActive(): bool
    {
        return $this->state->isActive();
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->isActive();
    }

    public function isOnStock(): bool
    {
        return $this->stock > 0;
    }

    public function title(): string
    {
        return $this->ext_title ?? $this->name;
    }

    public function getTitleAttribute(): string
    {
        return $this->title();
    }

    public function scopeActives(Builder $query): Builder
    {
        return $query->whereIn(
            'state',
            ProductStateProxy::getActiveStates()
        );
    }

    public function hasDimensions(): bool
    {
        return null !== $this->width && null !== $this->height && null !== $this->length;
    }

    public function dimension(): ?Dimension
    {
        if (!$this->hasDimensions()) {
            return null;
        }

        return new class ($this->width, $this->height, $this->length) implements Dimension {
            public function __construct(private float $width, private float $height, private float $length)
            {
            }

            public function width(): float
            {
                return $this->width;
            }

            public function height(): float
            {
                return $this->height;
            }

            public function length(): float
            {
                return $this->length;
            }
        };
    }
}
