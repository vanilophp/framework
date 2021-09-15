<?php

declare(strict_types=1);

namespace Vanilo\Category\Tests\Dummies;

use Vanilo\Category\Models\Taxon as BaseTaxon;

class Taxon extends BaseTaxon
{
    public function products()
    {
        return $this->morphedByMany(Product::class, 'model', 'model_taxons', 'taxon_id', 'model_id');
    }
}
