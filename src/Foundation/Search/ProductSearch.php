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
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Traits\Macroable;
use Konekt\Search\Exceptions\UnsupportedOperationException;
use Konekt\Search\Facades\Search;
use Konekt\Search\Searcher;
use Vanilo\Category\Contracts\Taxon;
use Vanilo\Channel\Contracts\Channel;
use Vanilo\MasterProduct\Contracts\MasterProduct;
use Vanilo\MasterProduct\Models\MasterProductProxy;
use Vanilo\MasterProduct\Models\MasterProductVariantProxy;
use Vanilo\Product\Contracts\Product;
use Vanilo\Product\Models\ProductProxy;
use Vanilo\Product\Models\ProductStateProxy;
use Vanilo\Properties\Contracts\PropertyValue;
use Vanilo\Properties\Models\PropertyValueProxy;

class ProductSearch
{
    use Macroable;

    protected Searcher $searcher;

    protected Builder $productQuery;

    protected Builder $masterProductQuery;

    protected ?Builder $variantQuery = null;

    protected ?string $orderBy = null;

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

    public static function findBySlug(string $slug): null|MasterProduct|Product
    {
        $instance = new static();
        $instance->productQuery->where('slug', $slug);
        $instance->masterProductQuery->where('slug', $slug);

        return $instance->getResults()->first();
    }

    public static function findBySlugOrFail(string $slug): MasterProduct|Product
    {
        $result = static::findBySlug($slug);
        if (null === $result) {
            throw (new ModelNotFoundException())->setModel(ProductProxy::modelClass());
        }

        return $result;
    }

    public function withinTaxon(Taxon $taxon): self
    {
        $this->productQuery->whereHas('taxons', function ($query) use ($taxon) {
            $query->where('id', $taxon->id);
        });

        $this->masterProductQuery->whereHas('taxons', function ($query) use ($taxon) {
            $query->where('id', $taxon->id);
        });

        $this->variantQuery?->whereHas('masterProduct', function ($query) use ($taxon) {
            $query->whereHas('taxons', function ($query) use ($taxon) {
                $query->where('id', $taxon->id);
            });
        });

        return $this;
    }

    public function orWithinTaxon(Taxon $taxon): self
    {
        $this->productQuery->orWhereHas('taxons', function ($query) use ($taxon) {
            $query->where('id', $taxon->id);
        });
        $this->masterProductQuery->orWhereHas('taxons', function ($query) use ($taxon) {
            $query->where('id', $taxon->id);
        });
        $this->variantQuery?->orWhereHas('masterProduct', function ($query) use ($taxon) {
            $query->whereHas('taxons', function ($query) use ($taxon) {
                $query->where('id', $taxon->id);
            });
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
        $this->variantQuery?->whereHas('masterProduct', function ($query) use ($taxonIds) {
            $query->whereHas('taxons', function ($query) use ($taxonIds) {
                $query->whereIn('id', $taxonIds);
            });
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
        $this->variantQuery?->orWhereHas('masterProduct', function ($query) use ($taxonIds) {
            $query->whereHas('taxons', function ($query) use ($taxonIds) {
                $query->whereIn('id', $taxonIds);
            });
        });

        return $this;
    }

    public function withinChannel(Channel $channel): self
    {
        return $this->withinChannels($channel);
    }

    public function withinChannels(Channel ...$channels): self
    {
        $channelIds = collect($channels)->pluck('id');

        $this->productQuery->whereHas('channels', function ($query) use ($channelIds) {
            $query->whereIn('channel_id', $channelIds);
        });
        $this->masterProductQuery->whereHas('channels', function ($query) use ($channelIds) {
            $query->whereIn('channel_id', $channelIds);
        });
        $this->variantQuery?->whereHas('masterProduct', function ($query) use ($channelIds) {
            $query->whereHas('channels', function ($query) use ($channelIds) {
                $query->whereIn('channel_id', $channelIds);
            });
        });

        return $this;
    }

    public function nameContains(string $term): self
    {
        $this->productQuery->where('name', 'like', "%$term%");
        $this->masterProductQuery->where('name', 'like', "%$term%");
        $this->variantQuery?->where('name', 'like', "%$term%");

        return $this;
    }

    public function priceBetween(float $min, float $max): self
    {
        $this->productQuery->whereBetween('price', [$min, $max]);
        $this->masterProductQuery->whereBetween('price', [$min, $max]);
        $this->variantQuery?->whereBetween('price', [$min, $max]);

        return $this;
    }

    public function priceGreaterThan(float $min): self
    {
        $this->productQuery->where('price', '>', $min);
        $this->masterProductQuery->where('price', '>', $min);
        $this->variantQuery?->where('price', '>', $min);

        return $this;
    }

    public function priceGreaterThanOrEqualTo(float $min): self
    {
        $this->productQuery->where('price', '>=', $min);
        $this->masterProductQuery->where('price', '>=', $min);
        $this->variantQuery?->where('price', '>=', $min);

        return $this;
    }

    public function priceLessThan(float $max): self
    {
        $this->productQuery->where('price', '<', $max);
        $this->masterProductQuery->where('price', '<', $max);
        $this->variantQuery?->where('price', '<', $max);

        return $this;
    }

    public function priceLessThanOrEqualTo(float $max): self
    {
        $this->productQuery->where('price', '<=', $max);
        $this->masterProductQuery->where('price', '<=', $max);
        $this->variantQuery?->where('price', '<=', $max);

        return $this;
    }

    public function nameStartsWith(string $term): self
    {
        $this->productQuery->where('name', 'like', "$term%");
        $this->masterProductQuery->where('name', 'like', "$term%");
        $this->variantQuery?->where('name', 'like', "$term%");

        return $this;
    }

    public function orNameStartsWith(string $term): self
    {
        $this->productQuery->orWhere('name', 'like', "$term%");
        $this->masterProductQuery->orWhere('name', 'like', "$term%");
        $this->variantQuery?->orWhere('name', 'like', "$term%");

        return $this;
    }

    public function nameEndsWith(string $term): self
    {
        $this->productQuery->where('name', 'like', "%$term");
        $this->masterProductQuery->where('name', 'like', "%$term");
        $this->variantQuery?->where('name', 'like', "%$term");

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
        $this->variantQuery?->whereHas('propertyValues', function ($query) use ($propertyValue) {
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
        $this->variantQuery?->orWhereHas('propertyValues', function ($query) use ($propertyValue) {
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
        $this->variantQuery?->whereHas('propertyValues', function ($query) use ($propertyValueIds) {
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
        $this->variantQuery?->orWhereHas('propertyValues', function ($query) use ($propertyValueIds) {
            $query->whereIn('id', $propertyValueIds);
        });

        return $this;
    }

    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->orderBy = "$column:$direction";

        return $this;
    }

    public function slugEquals(string $slug): self
    {
        $this->productQuery->where('slug', '=', $slug);
        $this->masterProductQuery->where('slug', '=', $slug);

        return $this;
    }

    public function withInactiveProducts(): self
    {
        $this->productQuery->withoutGlobalScope('withoutInactiveProducts');
        $this->masterProductQuery->withoutGlobalScope('withoutInactiveProducts');

        return $this;
    }

    public function withImages(): self
    {
        $this->productQuery->with('media');
        $this->masterProductQuery->with(['media', 'variants.media']);
        $this->variantQuery?->with('media');

        return $this;
    }

    public function withChannels(): self
    {
        $this->productQuery->with('channels');
        $this->masterProductQuery->with('channels');
        $this->variantQuery?->with('masterProduct.channels');

        return $this;
    }

    public function getSearcher(string|array $columns = null): Searcher
    {
        [$orderBy, $direction] = is_null($this->orderBy) ? [null, 'asc'] : explode(':', $this->orderBy);
        if ('desc' === strtolower($direction)) {
            $this->searcher->orderByDesc();
        }

        return $this->searcher
            ->add($this->productQuery, $columns, $orderBy)
            ->add($this->masterProductQuery, $columns, $orderBy)
            ->when(null !== $this->variantQuery, fn ($search) => $search->add($this->variantQuery, $columns, $orderBy));
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

    public function getResults(int $limit = null): Collection
    {
        return is_null($limit) ? $this->getSearcher()->search() : $this->getSearcher()->simplePaginate($limit)->search()->getCollection();
    }

    /**
     * @throws UnsupportedOperationException
     */
    public function includeVariants(): self
    {
        if ('pgsql' === DB::connection()->getDriverName()) {
            throw new UnsupportedOperationException('The `includeVariants()` feature is not supported on PostgreSQL databases.');
        }
        $this->variantQuery = MasterProductVariantProxy::query();

        return $this;
    }
}
