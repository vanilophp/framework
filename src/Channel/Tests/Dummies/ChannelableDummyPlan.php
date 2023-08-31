<?php

declare(strict_types=1);

/**
 * Contains the ChannelableDummyPlan test class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-08-31
 *
 */

namespace Vanilo\Channel\Tests\Dummies;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Vanilo\Channel\Models\Channel;

class ChannelableDummyPlan extends Model
{
    protected $guarded = ['id'];

    public function channels(): MorphToMany
    {
        return $this->morphToMany(Channel::class, 'channelable');
    }
}
