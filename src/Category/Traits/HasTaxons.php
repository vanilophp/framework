<?php

declare(strict_types=1);

/**
 * Contains the HasTaxons trait.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-02-03
 *
 */

namespace Vanilo\Category\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Vanilo\Category\Contracts\Taxon;
use Vanilo\Category\Contracts\Taxonomy;
use Vanilo\Category\Models\TaxonomyProxy;
use Vanilo\Category\Models\TaxonProxy;

trait HasTaxons
{
    public function taxons(): MorphToMany
    {
        return $this->morphToMany(
            TaxonProxy::modelClass(),
            'model',
            'model_taxons',
            'model_id',
            'taxon_id'
        );
    }

    public function activeTaxons(): MorphToMany
    {
        return $this->taxons()->where('is_active', true);
    }

    public function addTaxon(Taxon $taxon): void
    {
        $this->taxons()->attach($taxon);
    }

    public function addTaxons(iterable $taxons)
    {
        foreach ($taxons as $taxon) {
            if (!$taxon instanceof Taxon) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Every element passed to addTaxons must be a Taxon object. Given `%s`.',
                        is_object($taxon) ? get_class($taxon) : gettype($taxon)
                    )
                );
            }
        }

        return $this->taxons()->saveMany($taxons);
    }

    public function removeTaxon(Taxon $taxon)
    {
        return $this->taxons()->detach($taxon);
    }

    public function taxonsIn(string|Taxonomy $taxonomy, bool $withInactive = false): Collection
    {
        $taxonomy = match (true) {
            is_string($taxonomy) => TaxonomyProxy::findOneBySlug($taxonomy),
            default => $taxonomy,
        };

        if (null === $taxonomy) {
            return collect();
        }

        return $this
            ->taxons()
            ->where('taxonomy_id', $taxonomy->id)
            ->when(!$withInactive, fn ($query) => $query->activeOnes())
            ->get();
    }

    public function firstTaxonIn(string|Taxonomy $taxonomy, bool $withInactive = false): ?Taxon
    {
        $taxonomy = match (true) {
            is_string($taxonomy) => TaxonomyProxy::findOneBySlug($taxonomy),
            default => $taxonomy,
        };

        if (null === $taxonomy) {
            return null;
        }

        return $this
            ->taxons()
            ->where('taxonomy_id', $taxonomy->id)
            ->when(!$withInactive, fn ($query) => $query->activeOnes())
            ->first();
    }
}
