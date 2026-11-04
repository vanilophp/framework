<?php

declare(strict_types=1);

namespace Vanilo\Snapshot\Models;

use Carbon\CarbonImmutable;
use DateTimeImmutable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Vanilo\Snapshot\Contracts\Snapshot as SnapshotContract;
use Vanilo\Snapshot\SnapshotTypes;

/**
 * @property int $id
 * @property CarbonImmutable $taken_at
 * @property CarbonImmutable $valid_until
 * @property string $type
 * @property int $version
 * @property string $model_type
 * @property int|string $model_id
 * @property array $data
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 *
 * @property-read Model $model
 *
 * @method static Snapshot create(DateTimeInterface $takenAt, DateTimeInterface $validUntil, int $version = 1)
 */
class Snapshot extends Model implements SnapshotContract
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'taken_at' => 'immutable_datetime',
        'valid_until' => 'immutable_datetime',
        'data' => 'array',
        'version' => 'integer',
    ];

    public function version(): int
    {
        return $this->version ?? 1;
    }

    public function takenAt(): DateTimeImmutable
    {
        if ($this->taken_at instanceof DateTimeImmutable) {
            return $this->taken_at;
        }

        return Carbon::make($this->taken_at)->toImmutable();
    }

    public function validUntil(): DateTimeImmutable
    {
        if ($this->valid_until instanceof DateTimeImmutable) {
            return $this->valid_until;
        }

        return Carbon::make($this->valid_until)->toImmutable();
    }

    public function getData(): array
    {
        return $this->data ?? [];
    }

    public function asTypedSnapshot(): SnapshotContract
    {
        SnapshotTypes::make($this->type, [
            'taken_at' => $this->taken_at,
            'valid_until' => $this->valid_until,
            'data' => $this->data,
            'version' => $this->version,
        ]);
    }

    public static function create(
        DateTimeInterface $takenAt,
        DateTimeInterface $validUntil,
        int $version = 1,
    ): static {
        return new static([
            'taken_at' => $takenAt,
            'valid_until' => $validUntil,
            'version' => $version,
        ]);
    }

    public function load(array $data): void
    {
        $this->data = $data;
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
