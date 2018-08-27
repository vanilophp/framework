<?php
/**
 * Contains the ModuleServiceProvider class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-08-24
 */

namespace Vanilo\Category\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Category\Models\Taxon;
use Vanilo\Category\Models\Taxonomy;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Taxonomy::class,
        Taxon::class
    ];
}
