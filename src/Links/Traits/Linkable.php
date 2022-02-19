<?php

declare(strict_types=1);

/**
 * Contains the Linkable trait.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-18
 *
 */

namespace Vanilo\Links\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Query\Builder;
use Vanilo\Links\Contracts\LinkType;
use Vanilo\Links\Models\LinkGroupItemProxy;
use Vanilo\Links\Models\LinkTypeProxy;

/**
 * @property-read Collection $includedInLinkGroupItems
 */
trait Linkable
{
    private array $linkTypeCache = [];

    /**
     * Returns the models (other linkables) that are linked to this model with
     * the given link type. Since there can be multiple groups of the given
     * type, an optional property id can be passed, to further filter by
     */
    public function links(LinkType|string $type, $propertyId = null): Collection
    {
        $type = $this->normalizeLinkTypeModel($type);

        // @todo Optimize this to a single query
        $result = Collection::make();
        foreach ($this->linkGroups()->filter(fn ($group) => $group->type->id === $type->id) as $group) {
            $result->push(...$group
                ->items
                ->map
                ->linkable
                ->reject(fn ($item) => $item->id === $this->id)
            );
        }

        return $result;
    }

    public function linkGroups(): Collection
    {
        return $this->includedInLinkGroupItems->transform(fn($item) => $item->group);
    }

    public function includedInLinkGroupItems(): MorphMany
    {
        return $this->morphMany(LinkGroupItemProxy::modelClass(), 'linkable');
    }

    private function normalizeLinkTypeModel(LinkType|string $type): LinkType
    {
        $slug = is_string($type) ? $type : $type->slug;

        if (!isset($this->linkTypeCache[$slug])) {
            $this->linkTypeCache[$slug] = is_string($type) ? LinkTypeProxy::findBySlug($type) : $type;
        }

        return $this->linkTypeCache[$slug];
    }
}
