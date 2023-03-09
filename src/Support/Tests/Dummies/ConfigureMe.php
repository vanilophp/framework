<?php

declare(strict_types=1);

/**
 * Contains the ConfigureMe class.
 *
 * @copyright   Copyright (c) 2023 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-01-10
 *
 */

namespace Vanilo\Support\Tests\Dummies;

use Illuminate\Database\Eloquent\Model;
use Vanilo\Contracts\Configurable;
use Vanilo\Support\Traits\ConfigurableModel;

class ConfigureMe extends Model implements Configurable
{
    use ConfigurableModel;

    protected $guarded = [];

    protected $casts = ['configuration' => 'json'];
}
