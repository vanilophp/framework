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

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Vanilo\Channel\Contracts\Channel;
use Vanilo\Channel\Models\ChannelProxy;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection $channels
 * @method static Builder withinChannels(Collection|Channel|array $channels)
 */
trait Channelable
{
    public function channels(): MorphToMany
    {
        return $this->morphToMany(ChannelProxy::modelClass(), 'channelable', 'channelables', 'channelable_id', 'channel_id');
    }

    public function isAssignedToAnyChannel(): bool
    {
        return $this->channels->isNotEmpty();
    }

    public function isNotAssignedToAnyChannel(): bool
    {
        return !$this->isAssignedToAnyChannel();
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

    public function scopeWithinChannels(Builder $query, Collection|Channel|array $channels): Builder
    {
        $ids = [];
        if ($channels instanceof Channel) {
            $ids[] = $channels->getKey();
        } else {
            foreach ($channels as $channel) {
                $ids[] = $channel instanceof Channel ? $channel->id : $channel;
            }
        }

        return $query->whereHas('channels', fn ($query) => $query->whereIn('channel_id', $ids));
    }
}
