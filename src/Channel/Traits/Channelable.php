<?php

declare(strict_types=1);

/**
 * Contains the Channelable trait.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-09-07
 *
 */

namespace Vanilo\Channel\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Vanilo\Channel\Models\Channel;

trait Channelable
{
    public function channels(): MorphToMany
    {
        return $this->morphToMany(Channel::class, 'channelable');
    }
}
