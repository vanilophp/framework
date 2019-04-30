<?php
/**
 * Contains the ProductFinder class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-02-03
 *
 */

namespace Vanilo\Framework\Search;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Vanilo\Category\Contracts\Taxon;
use Vanilo\Product\Models\ProductProxy;
use Vanilo\Product\Models\ProductStateProxy;
use Vanilo\Properties\Contracts\PropertyValue;
use Vanilo\Properties\Models\PropertyValueProxy;

class ProductFinder
{
    /** @var Builder */
    private $queryBuilder;

    private $excludeInactiveProducts = true;

    public function __construct()
    {
        $this->queryBuilder = ProductProxy::query();
    }

    public function withinTaxon(Taxon $taxon): self
    {
        $this->queryBuilder->whereHas('taxons', function ($query) use ($taxon) {
            $query->where('id', $taxon->id);
        });

        return $this;
    }

    public function orWithinTaxon(Taxon $taxon): self
    {
        $this->queryBuilder->orWhereHas('taxons', function ($query) use ($taxon) {
            $query->where('id', $taxon->id);
        });

        return $this;
    }

    public function withinTaxons(array $taxons): self
    {
        $taxonIds = collect($taxons)->pluck('id');

        $this->queryBuilder->whereHas('taxons', function ($query) use ($taxonIds) {
            $query->whereIn('id', $taxonIds);
        });

        return $this;
    }

    public function orWithinTaxons(array $taxons): self
    {
        $taxonIds = collect($taxons)->pluck('id');

        $this->queryBuilder->orWhereHas('taxons', function ($query) use ($taxonIds) {
            $query->whereIn('id', $taxonIds);
        });

        return $this;
    }

    public function nameContains(string $term): self
    {
        $this->queryBuilder->where('name', 'like', "%$term%");

        return $this;
    }

    public function orNameContains(string $term): self
    {
        $this->queryBuilder->orWhere('name', 'like', "%$term%");

        return $this;
    }

    public function nameStartsWith(string $term): self
    {
        $this->queryBuilder->where('name', 'like', "$term%");

        return $this;
    }

    public function orNameStartsWith(string $term): self
    {
        $this->queryBuilder->orWhere('name', 'like', "$term%");

        return $this;
    }

    public function nameEndsWith(string $term): self
    {
        $this->queryBuilder->where('name', 'like', "%$term");

        return $this;
    }

    public function orNameEndsWith(string $term): self
    {
        $this->queryBuilder->orWhere('name', 'like', "%$term");

        return $this;
    }

    public function havingPropertyValue(PropertyValue $propertyValue): self
    {
        $this->queryBuilder->whereHas('propertyValues', function ($query) use ($propertyValue) {
            $query->where('id', $propertyValue->id);
        });

        return $this;
    }

    public function orHavingPropertyValue(PropertyValue $propertyValue): self
    {
        $this->queryBuilder->orWhereHas('propertyValues', function ($query) use ($propertyValue) {
            $query->where('id', $propertyValue->id);
        });

        return $this;
    }

    public function havingPropertyValues(array $propertyValues): self
    {
        $propertyValueIds = collect($propertyValues)->pluck('id');

        $this->queryBuilder->whereHas('propertyValues', function ($query) use ($propertyValueIds) {
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

        $this->queryBuilder->orWhereHas('propertyValues', function ($query) use ($propertyValueIds) {
            $query->whereIn('id', $propertyValueIds);
        });

        return $this;
    }

    public function withInactiveProducts()
    {
        $this->excludeInactiveProducts = false;

        return $this;
    }

    public function getResults(): Collection
    {
        if ($this->excludeInactiveProducts) {
            $this->queryBuilder->whereIn(
                'state',
                ProductStateProxy::getActiveStates()
            );
        }
        return $this->queryBuilder->get();
    }
    public function getQueryBuilder(): Builder
    {
        return $this->queryBuilder;
    }
}
