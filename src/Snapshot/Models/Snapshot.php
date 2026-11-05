<?php

declare(strict_types=1);

namespace Vanilo\Snapshot\Models;

use Carbon\CarbonImmutable;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Vanilo\Snapshot\Concerns\CalculatesSnapshotValidity;
use Vanilo\Snapshot\Contracts\Snapshot as SnapshotContract;
use Vanilo\Snapshot\Contracts\SnapshotPayload;
use Vanilo\Snapshot\Contracts\Snapshottable;
use Vanilo\Snapshot\Exceptions\UnknownSnapshotTypeException;
use Vanilo\Snapshot\SnapshotMetadata;
use Vanilo\Snapshot\SnapshotPayloadTypes;

/**
 * @property int $id
 * @property CarbonImmutable $taken_at
 * @property CarbonImmutable|null $valid_until
 * @property string $type
 * @property int $version
 * @property string $snapshottable_type
 * @property int|string $snapshottable_id
 * @property array $data
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 *
 * @property-read Model|null $snapshottable
 */
class Snapshot extends Model implements SnapshotContract
{
    use CalculatesSnapshotValidity;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'taken_at' => 'immutable_datetime',
        'valid_until' => 'immutable_datetime',
        'data' => 'array',
        'version' => 'integer',
    ];

    protected ?SnapshotPayload $payload = null;

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

    public function validUntil(): ?DateTimeImmutable
    {
        if (null === $this->valid_until || $this->valid_until instanceof DateTimeImmutable) {
            return $this->valid_until;
        }

        return Carbon::make($this->valid_until)->toImmutable();
    }

    public function getRawData(): array
    {
        return $this->data ?? [];
    }

    public static function take(Snapshottable $source): static
    {
        $payload = $source->toSnapshotPayload();

        $snapshot = new static([
            'type' => SnapshotPayloadTypes::getIdOf($payload::class) ?? throw new UnknownSnapshotTypeException('Snapshot payload type not found'),
            'version' => $payload->version(),
            'taken_at' => $payload->takenAt(),
            'valid_until' => $payload->validUntil(),
            'data' => $payload->toArray(),
        ]);

        if ($source instanceof Model) {
            $snapshot->snapshottable()->associate($source);
        }

        return $snapshot; // the caller decides when to save()
    }

    public function payload(): SnapshotPayload
    {
        return $this->payload ??= SnapshotPayloadTypes::make($this->type, [
            'data' => $this->data ?? [],
            'meta' => new SnapshotMetadata($this->version(), $this->takenAt(), $this->validUntil()),
        ]);
    }

    public function snapshottable(): MorphTo
    {
        return $this->morphTo();
    }
}
