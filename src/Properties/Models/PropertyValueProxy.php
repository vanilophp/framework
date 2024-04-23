<?php

declare(strict_types=1);

/**
 * Contains the PropertyValueProxy class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Properties\Models;

use Illuminate\Database\Eloquent\Collection;
use Konekt\Concord\Proxies\ModelProxy;

/**
 * @method static PropertyValue|null findByPropertyAndValue(string $propertySlug, mixed $value)
 * @method static Collection getByScalarPropertiesAndValues(array $conditions)
 */
class PropertyValueProxy extends ModelProxy
{
}
