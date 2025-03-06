<?php

declare(strict_types=1);

namespace Vanilo\Video\Tests\Examples;

use Illuminate\Database\Eloquent\Model;
use Vanilo\Video\Traits\HasVideos;

/**
 * @method static Product create(array $attributes = [])
 */
class Product extends Model
{
    use HasVideos;

    protected $guarded = ['id'];
}
