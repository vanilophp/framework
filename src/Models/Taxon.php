<?php
/**
 * Contains the Taxon class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-08-27
 *
 */

namespace Vanilo\Category\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Vanilo\Category\Contracts\Taxon as TaxonContract;

class Taxon extends Model implements TaxonContract
{
    use Sluggable, SluggableScopeHelpers;

    protected $table = 'taxons';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function taxonomy(): BelongsTo
    {
        return $this->belongsTo(TaxonomyProxy::modelClass(), 'taxonomy_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(TaxonProxy::modelClass(), 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(TaxonProxy::modelClass(), 'parent_id');
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
