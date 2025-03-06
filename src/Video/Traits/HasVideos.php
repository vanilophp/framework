<?php

declare(strict_types=1);

namespace Vanilo\Video\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Vanilo\Video\Models\VideoProxy;

trait HasVideos
{
    public function videos(): MorphToMany
    {
        return $this->morphToMany(
            VideoProxy::modelClass(),
            'model',
            'model_videos',
            'model_id',
            'video_id'
        );
    }
}
