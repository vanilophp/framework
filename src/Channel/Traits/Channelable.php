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

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Vanilo\Channel\Contracts\Channel;
use Vanilo\Channel\Models\ChannelProxy;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection $channels
 */
trait Channelable
{
    public function channels(): MorphToMany
    {
        return $this->morphToMany(ChannelProxy::modelClass(), 'channelable');
    }

    public function isInChannel(Channel|int|string $channel): bool
    {
        return $this->channels->contains($channel);
    }

    public function isNotInChannel(Channel|int|string $channel): bool
    {
        return !$this->isInChannel($channel);
    }

    public function assignChannels(array|Model|Collection $channels): void
    {
        $this->channels()->sync($channels);
    }

    public function removeFromAllChannels(): void
    {
        $this->channels()->detach();
    }
}
