<?php

declare(strict_types=1);

/**
 * Contains the Property class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Properties\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Vanilo\Properties\Contracts\Property as PropertyContract;
use Vanilo\Properties\Contracts\PropertyType;
use Vanilo\Properties\Exceptions\UnknownPropertyTypeException;
use Vanilo\Properties\PropertyTypes;

/**
 * @property string     $name
 * @property string     $slug
 * @property string     $type
 * @property array      $configuration
 * @property bool       $is_hidden
 * @property Collection $propertyValues
 *
 * @method static Builder hiddenOnes()
 * @method static Builder visibleOnes()
 */
class Property extends Model implements PropertyContract
{
    use Sluggable;
    use SluggableScopeHelpers {
        findBySlug as protected sluggableFindBySlug;
    }

    protected $table = 'properties';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'configuration' => 'array',
        'is_hidden' => 'boolean',
    ];

    public function getType(): PropertyType
    {
        $class = PropertyTypes::getClass($this->type);

        if (!$class) {
            throw new UnknownPropertyTypeException(
                sprintf('Unknown property type `%s`', $this->type)
            );
        }

        return new $class();
    }

    public static function findBySlug(string $slug): ?PropertyContract
    {
        return static::sluggableFindBySlug($slug);
    }

    public static function findOneByName(string $name): ?PropertyContract
    {
        return PropertyProxy::where('name', $name)->first();
    }

    public function values(): Collection
    {
        return $this->propertyValues()->sort()->get();
    }

    public function propertyValues(): HasMany
    {
        return $this->hasMany(PropertyValueProxy::modelClass());
    }

    public function scopeHiddenOnes(Builder $query): Builder
    {
        return $query->where('is_hidden', true);
    }

    public function scopeVisibleOnes(Builder $query): Builder
    {
        return $query->where('is_hidden', false);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }
}
