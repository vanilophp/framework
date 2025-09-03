<?php

declare(strict_types=1);

namespace Vanilo\Video\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Vanilo\Video\Contracts\Video as VideoContract;
use Vanilo\Video\Contracts\VideoDriver;
use Vanilo\Video\Dto\Stats;
use Vanilo\Video\Dto\Thumbnail;
use Vanilo\Video\VideoDrivers;

/**
 * @property int $id
 * @property string $hash
 * @property string $driver
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

    protected static array $driverCache = [];

    public function __construct(array $attributes = [])
    {
        if (!isset($attributes['hash'])) {
            $this->generateHash();
        }

        parent::__construct($attributes);
    }

    public function getId(): string|int
    {
        return $this->id;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getDriver(): VideoDriver
    {
        return static::$driverCache[$this->driver] ??= VideoDrivers::make($this->driver, Arr::wrap(config('vanilo.video.' . $this->driver, [])));
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function getThumbnail(array $options = []): Thumbnail
    {
        return $this->getDriver()->getThumbnail($this, $options);
    }

    public function getVideoUrl(array $options = []): ?string
    {
        $driver = $this->getDriver();
        $caps = $driver::capabilities();

        if ($caps->fileUpload) {
            return $driver->getFileUrl($this, $options);
        } elseif ($caps->streaming) {
            return $driver->getStreamUrl($this, $options);
        }

        return $driver->getVideoUrl($this, $options);
    }

    public function getEmbedCode(array $options = []): ?string
    {
        return $this->getDriver()->getEmbedCode($this, $options);
    }

    public function stats(): Stats
    {
        return $this->getDriver()->stats($this);
    }

    public function isPublished(): bool
    {
        return $this->is_published;
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    private function generateHash(): void
    {
        $this->setRawAttributes(
            array_merge(
                $this->attributes,
                [
                    'hash' => Str::ulid()->toBase58(),
                ]
            ),
            true
        );
    }
}
