<?php

declare(strict_types=1);

/**
 * Contains the EliminateLinks class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-23
 *
 */

namespace Vanilo\Links\Query;

use Illuminate\Database\Eloquent\Model;
use Vanilo\Links\Contracts\LinkType;
use Vanilo\Links\Models\LinkGroup;
use Vanilo\Links\Models\LinkGroupItemProxy;

final class EliminateLinks
{
    use HasBaseModel;
    use FindsDesiredLinkGroups;

    public function __construct(
        private LinkType $type
    ) {
    }

    public function and(Model ...$models): void
    {
        $toRemove = collect($models);
        /** @var LinkGroup $group */
        foreach ($this->linkGroupsOfModel($this->baseModel) as $group) {
            $itemsToDelete = $group
                ->items
                ->filter(fn ($item) => $toRemove->contains(function ($modelToRemove) use ($item) {
                    return $modelToRemove->id == $item->linkable_id &&
                        $modelToRemove::class === $item->linkable_type;
                }));
            LinkGroupItemProxy::destroy($itemsToDelete->map->id);
        }
    }
}
