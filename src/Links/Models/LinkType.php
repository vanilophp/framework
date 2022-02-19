<?php

declare(strict_types=1);

/**
 * Contains the LinkType class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-11
 *
 */

namespace Vanilo\Links\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Vanilo\Links\Contracts\LinkType as LinkTypeContract;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 * @method static LinkType create(array $attributes)
 * @method static Builder bySlug(string $slug)
 * @method static Builder active(string $slug)
 * @method static Builder inactive(string $slug)
 */
class LinkType extends Model implements LinkTypeContract
{
    use Sluggable;
    use SluggableScopeHelpers;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function findBySlug(string $slug): ?LinkTypeContract
    {
        return static::bySlug($slug)->first();
    }

    public function scopeBySlug(Builder $builder, string $slug): Builder
    {
        return $builder->where('slug', $slug);
    }

    public function scopeActive(Builder $builder): Builder
    {
        return $builder->where('is_active', true);
    }

    public function scopeInactive(Builder $builder): Builder
    {
        return $builder->where('is_active', false);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
