<?php

declare(strict_types=1);

namespace Vanilo\Video\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Vanilo\Video\Contracts\Video as VideoContract;

/**
 * @property int $id
 * @property string $hash
 * @property string $type
 * @property string|null $title
 * @property string|null $description
 * @property int|null $width
 * @property int|null $height
 * @property int|null $duration
 * @property int|null $size_in_bytes
 * @property string $reference
 * @property string|null $format
 * @property bool $is_published
 * @property array|null $data
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 *
 * @method static Video create(array $attributes)
 */
class Video extends Model implements VideoContract
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'is_published' => 'boolean',
        'data' => 'array',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
