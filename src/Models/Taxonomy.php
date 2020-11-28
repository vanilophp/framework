<?php
/**
 * Contains the Taxonomy class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-08-25
 */

namespace Vanilo\Category\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Vanilo\Category\Contracts\Taxonomy as TaxonomyContract;

class Taxonomy extends Model implements TaxonomyContract
{
    use Sluggable, SluggableScopeHelpers;

    protected $table = 'taxonomies';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public static function findOneByName(string $name): ?TaxonomyContract
    {
        return static::where('name', $name)->first();
    }

    public static function findOneBySlug(string $slug): ?TaxonomyContract
    {
        return static::where('slug', $slug)->first();
    }

    public function rootLevelTaxons(): Collection
    {
        return TaxonProxy::byTaxonomy($this)
                         ->roots()
                         ->sort()
                         ->get();
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
