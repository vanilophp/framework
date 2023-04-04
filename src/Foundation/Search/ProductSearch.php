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

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Konekt\Search\Facades\Search;
use Konekt\Search\Searcher;
use Vanilo\Foundation\Models\Taxon;
use Vanilo\MasterProduct\Models\MasterProductProxy;
use Vanilo\Product\Models\ProductProxy;
use Vanilo\Product\Models\ProductStateProxy;
use Vanilo\Properties\Contracts\PropertyValue;
use Vanilo\Properties\Models\PropertyValueProxy;

class ProductSearch
{
    private Searcher $searcher;

    private Builder $productQuery;

    private Builder $masterProductQuery;

    public function __construct()
    {
        $this->searcher = Search::new();
        $this->productQuery = ProductProxy::query()
            ->withGlobalScope('withoutInactiveProducts', function (Builder $queryBuilder) {
                return $queryBuilder->whereIn('state', ProductStateProxy::getActiveStates());
            });
        $this->masterProductQuery = MasterProductProxy::query()
            ->withGlobalScope('withoutInactiveProducts', function (Builder $queryBuilder) {
                return $queryBuilder->whereIn('state', ProductStateProxy::getActiveStates());
            });
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

    public function orWithinTaxon(\Vanilo\Category\Contracts\Taxon $taxon): self
    {
        $this->productQuery->orWhereHas('taxons', function ($query) use ($taxon) {
            $query->where('id', $taxon->id);
        });
        $this->masterProductQuery->orWhereHas('taxons', function ($query) use ($taxon) {
            $query->where('id', $taxon->id);
        });

        return $this;
    }

    public function withinTaxons(array $taxons): self
    {
        $taxonIds = collect($taxons)->pluck('id');

        $this->productQuery->whereHas('taxons', function ($query) use ($taxonIds) {
            $query->whereIn('id', $taxonIds);
        });
        $this->masterProductQuery->whereHas('taxons', function ($query) use ($taxonIds) {
            $query->whereIn('id', $taxonIds);
        });

        return $this;
    }

    public function orWithinTaxons(array $taxons): self
    {
        $taxonIds = collect($taxons)->pluck('id');

        $this->productQuery->orWhereHas('taxons', function ($query) use ($taxonIds) {
            $query->whereIn('id', $taxonIds);
        });
        $this->masterProductQuery->orWhereHas('taxons', function ($query) use ($taxonIds) {
            $query->whereIn('id', $taxonIds);
        });

        return $this;
    }

    public function nameContains(string $term): self
    {
        $this->productQuery->where('name', 'like', "%$term%");
        $this->masterProductQuery->where('name', 'like', "%$term%");

        return $this;
    }

    public function nameStartsWith(string $term): self
    {
        $this->productQuery->where('name', 'like', "$term%");
        $this->masterProductQuery->where('name', 'like', "$term%");

        return $this;
    }

    public function orNameStartsWith(string $term): self
    {
        $this->productQuery->orWhere('name', 'like', "$term%");
        $this->masterProductQuery->orWhere('name', 'like', "$term%");

        return $this;
    }

    public function nameEndsWith(string $term): self
    {
        $this->productQuery->where('name', 'like', "%$term");
        $this->masterProductQuery->where('name', 'like', "%$term");

        return $this;
    }

    public function havingPropertyValue(PropertyValue $propertyValue): self
    {
        $this->productQuery->whereHas('propertyValues', function ($query) use ($propertyValue) {
            $query->where('id', $propertyValue->id);
        });
        $this->masterProductQuery->whereHas('propertyValues', function ($query) use ($propertyValue) {
            $query->where('id', $propertyValue->id);
        });

        return $this;
    }

    public function orHavingPropertyValue(PropertyValue $propertyValue): self
    {
        $this->productQuery->orWhereHas('propertyValues', function ($query) use ($propertyValue) {
            $query->where('id', $propertyValue->id);
        });
        $this->masterProductQuery->orWhereHas('propertyValues', function ($query) use ($propertyValue) {
            $query->where('id', $propertyValue->id);
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

    public function orHavingPropertyValues(array $propertyValues): self
    {
        $propertyValueIds = collect($propertyValues)->pluck('id');

        $this->productQuery->orWhereHas('propertyValues', function ($query) use ($propertyValueIds) {
            $query->whereIn('id', $propertyValueIds);
        });
        $this->masterProductQuery->orWhereHas('propertyValues', function ($query) use ($propertyValueIds) {
            $query->whereIn('id', $propertyValueIds);
        });

        return $this;
    }

    public function withInactiveProducts(): self
    {
        $this->productQuery->withoutGlobalScope('withoutInactiveProducts');
        $this->masterProductQuery->withoutGlobalScope('withoutInactiveProducts');

        return $this;
    }

    public function getSearcher(): Searcher
    {
        return $this->searcher
            ->add($this->productQuery)
            ->add($this->masterProductQuery);
    }

    public function simplePaginate(int $perPage = 15, array $columns = ['*'], string $pageName = 'page', int $page = null): Paginator
    {
        return $this->getSearcher()->simplePaginate($perPage, $pageName, $page)->search();
    }

    /** @see Builder::paginate() */
    public function paginate(int $perPage = 15, array $columns = ['*'], string $pageName = 'page', int $page = null): LengthAwarePaginator
    {
        return $this->getSearcher()->paginate($perPage, $pageName, $page)->search();
    }

    public function getResults(): Collection
    {
        return $this->getSearcher()->search();
    }
}
