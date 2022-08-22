<?php

declare(strict_types=1);

/**
 * Contains the PropertyProxy class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Properties\Models;

use Illuminate\Support\Collection;
use Konekt\Concord\Proxies\ModelProxy;
use Vanilo\Properties\Contracts\PropertyType;

/**
 * @method static PropertyType getType()
 * @method static Collection values()
 * @method static \Vanilo\Properties\Contracts\Property|null findBySlug(string $slug)
 */
class PropertyProxy extends ModelProxy
{
}
