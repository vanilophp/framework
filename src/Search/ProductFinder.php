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

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Vanilo\Product\Models\ProductProxy;

class ProductFinder
{
    /** @var Builder */
    private $queryBuilder;

    public function __construct()
    {
        $this->queryBuilder = ProductProxy::query();
    }
    public function withinTaxons(array $taxons): self
    {
    }

    public function nameContains(string $term): self
    {
        $this->queryBuilder->orWhere('name', 'like', "%$term%");

        return $this;
    }

    public function nameStartsWith(string $term): self
    {
        $this->queryBuilder->orWhere('name', 'like', "$term%");

        return $this;
    }

    public function nameEndsWith(string $term): self
    {
        $this->queryBuilder->orWhere('name', 'like', "%$term");

        return $this;
    }

    public function havingPropertyValues(array $propertyValues): self
    {
    }

    public function getResults(): Collection
    {
        return $this->queryBuilder->get();
    }
}
