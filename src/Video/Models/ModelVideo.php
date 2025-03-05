<?php

declare(strict_types=1);

namespace Vanilo\Video\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Vanilo\Video\Contracts\Video as VideoContract;

class ModelVideo extends Model implements VideoContract
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function modelable(): MorphTo
    {
        return $this->morphTo();
    }
}
