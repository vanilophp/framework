<?php

declare(strict_types=1);

namespace Vanilo\Video\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use \Vanilo\Video\Contracts\Video as VideoContract;

class Video extends Model implements VideoContract
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'is_published' => 'boolean',
        'data' => 'array',
    ];

    public function model(): MorphOne
    {
        return $this->morphOne(ModelVideoProxy::modelClass(), 'modelable');
    }
}
