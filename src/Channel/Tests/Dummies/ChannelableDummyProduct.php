<?php

declare(strict_types=1);

/**
 * Contains the ChannelableDummyProduct class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-09-06
 *
 */

namespace Vanilo\Channel\Tests\Dummies;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Vanilo\Channel\Models\Channel;

class ChannelableDummyProduct extends Model
{
    protected $guarded = ['id'];

    public function channels(): MorphToMany
    {
        return $this->morphToMany(Channel::class, 'channelable');
    }
}
