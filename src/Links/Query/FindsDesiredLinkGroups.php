<?php

declare(strict_types=1);

/**
 * Contains the FindsDesiredLinkGroups trait.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-23
 *
 */

namespace Vanilo\Links\Query;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Vanilo\Links\Models\LinkGroupItemProxy;

trait FindsDesiredLinkGroups
{
    use HasPropertyFilter;

    protected function linkGroupsOfModel(Model $model): Collection
    {
        $groups = $model
            ->morphMany(LinkGroupItemProxy::modelClass(), 'linkable')
            ->get()
            ->transform(fn ($item) => $item->group)
            ->filter(fn ($group) => $group->type->id === $this->type->id);

        if ($this->hasPropertyFilter()) {
            $groups = $groups->filter(fn ($group) => $group->property_id == $this->propertyId());
        }

        return $groups;
    }
}
