<?php

declare(strict_types=1);

/**
 * Contains the EliminateLinkGroup class.
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
use Vanilo\Links\Models\LinkGroupProxy;

class EliminateLinkGroup
{
    use FindsDesiredLinkGroups;

    public function __construct(
        private LinkType $type
    ) {
    }

    public function of(Model $model): void
    {
        LinkGroupProxy::destroy($this->linkGroupsOfModel($model)->map->id);
    }
}
