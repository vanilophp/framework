<?php

declare(strict_types=1);

/**
 * Contains the ModuleServiceProvider class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-11
 *
 */

namespace Vanilo\Links\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Links\Models\LinkGroup;
use Vanilo\Links\Models\LinkGroupItem;
use Vanilo\Links\Models\LinkType;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        LinkType::class,
        LinkGroup::class,
        LinkGroupItem::class,
    ];
}
