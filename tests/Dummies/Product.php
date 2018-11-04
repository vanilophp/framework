<?php
/**
 * Contains the Product class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-11-04
 *
 */

namespace Vanilo\Category\Tests\Dummies;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Vanilo\Category\Models\Taxon;

class Product extends Model
{
    protected $guarded = ['id'];

    public function taxons(): MorphToMany
    {
        return $this->morphToMany(
            Taxon::class,
            'model',
            'model_taxons',
            'model_id',
            'taxon_id'
        );
    }
}
