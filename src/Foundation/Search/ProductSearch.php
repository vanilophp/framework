<?php

declare(strict_types=1);

/**
 * Contains the ProductSearch class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-27
 *
 */

namespace Vanilo\Foundation\Search;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Konekt\Search\Facades\Search;
use Konekt\Search\Searcher;
use Vanilo\Foundation\Models\Taxon;
use Vanilo\MasterProduct\Models\MasterProductProxy;
use Vanilo\Product\Models\ProductProxy;
use Vanilo\Properties\Models\PropertyValueProxy;

class ProductSearch
{
    private Searcher $searcher;

    private Builder $productQuery;

    private Builder $masterProductQuery;

    public function __construct()
    {
        $this->searcher = Search::new();
        $this->productQuery = ProductProxy::actives();
        $this->masterProductQuery = MasterProductProxy::actives();
    }

    public function withinTaxon(Taxon $taxon): self
    {
        $this->productQuery->whereHas('taxons', function ($query) use ($taxon) {
            $query->where('id', $taxon->id);
        });

        $this->masterProductQuery->whereHas('taxons', function ($query) use ($taxon) {
            $query->where('id', $taxon->id);
        });

        return $this;
    }

    public function havingPropertyValues(array $propertyValues): self
    {
        $propertyValueIds = collect($propertyValues)->pluck('id');

        $this->productQuery->whereHas('propertyValues', function ($query) use ($propertyValueIds) {
            $query->whereIn('id', $propertyValueIds);
        });

        $this->masterProductQuery->whereHas('propertyValues', function ($query) use ($propertyValueIds) {
            $query->whereIn('id', $propertyValueIds);
        });

        return $this;
    }

    public function havingPropertyValuesByName(string $property, array $values): self
    {
        return $this->havingPropertyValues(
            PropertyValueProxy::query()
                ->select('property_values.*')
                ->join('properties', 'properties.id', '=', 'property_values.property_id')
                ->where('properties.slug', '=', $property)
                ->whereIn('value', $values)
                ->get()
                ->all()
        );
    }

    public function getResults(): Collection
    {
        return $this->searcher
            ->add($this->productQuery)
            ->add($this->masterProductQuery)
            ->search();
    }
}
