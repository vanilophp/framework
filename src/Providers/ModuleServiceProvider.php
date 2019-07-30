<?php
/**
 * Contains the ModuleServiceProvider class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-07-10
 *
 */

namespace Vanilo\Channel\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Channel\Models\Channel;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Channel::class
    ];
}
