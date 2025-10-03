<?php

declare(strict_types=1);

/**
 * Contains the MasterProduct class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-06-13
 *
 */

namespace Vanilo\MasterProduct\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Konekt\Enum\Eloquent\CastsEnums;
use Vanilo\MasterProduct\Contracts\MasterProduct as MasterProductContract;
use Vanilo\MasterProduct\Tests\Factories\MasterProductFactory;
use Vanilo\Product\Models\ProductState;
use Vanilo\Product\Models\ProductStateProxy;

/**
 * @property int $id
 * @property string $name
 * @property string|null $slug
 * @property string|null $subtitle
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
 * @property int $priority
 * @property null|Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read bool $is_active
 * @property-read string|null $title
 *
 * @property-read Collection|MasterProductVariant[] $variants
 *
 * @method static MasterProduct create(array $attributes = [])
 */
class MasterProduct extends Model implements MasterProductContract
{
    use CastsEnums;
    use Sluggable;
    use SluggableScopeHelpers;
    use HasFactory;

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
        'state' => ProductStateProxy::class . '@enumClass',
    ];

    public function variants(): HasMany
    {
        return $this->hasMany(MasterProductVariantProxy::modelClass(), 'master_product_id', 'id');
    }

    public function createVariant(array $attributes): MasterProductVariant
    {
        $variant = MasterProductVariantProxy::create(
            array_merge(
                Arr::except($attributes, ['properties']),
                ['master_product_id' => $this->id],
            )
        );

        if (Arr::exists($attributes, 'properties') && method_exists($variant, 'assignPropertyValues')) {
            $variant->assignPropertyValues($attributes['properties']);
        }

        return $variant;
    }

    public function isActive(): bool
    {
        return $this->state->isActive();
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->isActive();
    }

    public function scopeActives(Builder $query): Builder
    {
        return $query->whereIn('state', ProductStateProxy::getActiveStates());
    }

    public function scopeInactives(Builder $query): Builder
    {
        return $query->whereIn('state', array_diff(ProductStateProxy::values(), ProductStateProxy::getActiveStates()));
    }

    public function title(): string
    {
        return $this->ext_title ?? $this->name;
    }

    public function getTitleAttribute(): string
    {
        return $this->title();
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected static function newFactory()
    {
        return MasterProductFactory::new();
    }
}
