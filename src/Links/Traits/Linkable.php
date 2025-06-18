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
use Vanilo\Links\Contracts\LinkType;
use Vanilo\Links\Models\LinkGroupItemProxy;

/**
 * @property-read Collection $includedInLinkGroupItems
 */
trait Linkable
{
    use NormalizesLinkType;

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

        $groups = $this->linkGroups()->filter(fn ($group) => $group->type->id === $type->id);
        foreach ($groups as $group) {
            if (is_null($group->root_item_id) || $group->rootItem->linkable_id === $this->id) {
                $result->push(
                    ...$group
                    ->items
                    ->map
                    ->linkable
                    ->reject(fn ($linkable) => $linkable->is($this))
                );
            }
        }

        return $result;
    }

    public function linkGroups(): Collection
    {
        return $this->includedInLinkGroupItems()->get()->transform(fn ($item) => $item->group);
    }

    public function includedInLinkGroupItems(): MorphMany
    {
        return $this->morphMany(LinkGroupItemProxy::modelClass(), 'linkable');
    }
}
