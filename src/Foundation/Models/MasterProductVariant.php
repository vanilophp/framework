<?php

declare(strict_types=1);

/**
 * Contains the MasterProductVariant class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-08-23
 *
 */

namespace Vanilo\Foundation\Models;

use Vanilo\MasterProduct\Models\MasterProductVariant as BaseMasterProductVariant;
use Vanilo\Properties\Traits\HasPropertyValues;

class MasterProductVariant extends BaseMasterProductVariant
{
    use HasPropertyValues;
}
