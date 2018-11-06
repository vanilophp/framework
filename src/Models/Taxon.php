<?php
/**
 * Contains the Taxon class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-11-06
 *
 */

namespace Vanilo\Framework\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Vanilo\Category\Models\Taxon as BaseTaxon;
use Vanilo\Product\Models\ProductProxy;

class Taxon extends BaseTaxon
{
    public function products(): MorphToMany
    {
        return $this->morphedByMany(
            ProductProxy::modelClass(), 'model', 'model_taxons', 'taxon_id', 'model_id'
        );
    }
}
