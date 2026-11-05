<?php

declare(strict_types=1);

namespace Vanilo\Snapshot\Tests;

use Carbon\CarbonImmutable;
use DateTimeImmutable;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Snapshot\Contracts\Snapshot as SnapshotContract;
use Vanilo\Snapshot\Contracts\SnapshotPayload;
use Vanilo\Snapshot\Contracts\Snapshottable;
use Vanilo\Snapshot\Models\Snapshot;
use Vanilo\Snapshot\Models\SnapshotProxy;
use Vanilo\Snapshot\SnapshotMetadata;
use Vanilo\Snapshot\SnapshotPayloadTypes;
use Vanilo\Snapshot\Tests\Dummies\DummySnapshotV1;

class SnapshotModelTest extends TestCase
{
    #[Test]
    public function it_implements_the_snapshot_interface(): void
    {
        $snapshot = new Snapshot();

        $this->assertInstanceOf(SnapshotContract::class, $snapshot);
    }

    #[Test]
    public function it_can_be_instantiated_with_attributes(): void
    {
        $takenAt = new DateTimeImmutable('2026-11-03 12:00:00');
        $validUntil = new DateTimeImmutable('2026-11-03 13:00:00');

        $snapshot = new Snapshot([
            'taken_at' => $takenAt,
            'valid_until' => $validUntil,
            'version' => 2,
        ]);

        $this->assertEquals($takenAt, $snapshot->takenAt());
        $this->assertEquals($validUntil, $snapshot->validUntil());
        $this->assertEquals(2, $snapshot->version());
    }

    #[Test]
    public function default_version_is_one(): void
    {
        $snapshot = new Snapshot();

        $this->assertEquals(1, $snapshot->version());
    }

    #[Test]
    public function taken_at_and_valid_until_return_datetime_immutable_instances(): void
    {
        $takenAt = Carbon::parse('2026-11-03 10:00:00');
        $validUntil = Carbon::parse('2026-11-03 11:00:00');

        $snapshot = new Snapshot([
            'taken_at' => $takenAt,
            'valid_until' => $validUntil,
        ]);

        $this->assertInstanceOf(DateTimeImmutable::class, $snapshot->takenAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $snapshot->validUntil());
        $this->assertTrue(CarbonImmutable::instance($takenAt)->eq($snapshot->takenAt()));
        $this->assertTrue(CarbonImmutable::instance($validUntil)->eq($snapshot->validUntil()));
    }

    #[Test]
    public function raw_data_returns_empty_array_when_not_set(): void
    {
        $snapshot = new Snapshot();

        $this->assertSame([], $snapshot->getRawData());
    }

    #[Test]
    public function raw_data_returns_the_data_attribute(): void
    {
        $data = ['sku' => 'PROD-1', 'price' => 19.99];
        $snapshot = new Snapshot(['data' => $data]);

        $this->assertSame($data, $snapshot->getRawData());
        $this->assertSame($data, $snapshot->data);
    }

    #[Test]
    public function it_can_be_persisted_to_the_database(): void
    {
        $snapshot = Snapshot::create([
            'taken_at' => now()->toImmutable(),
            'valid_until' => now()->addHour()->toImmutable(),
            'version' => 1,
            'type' => 'product',
            'snapshottable_type' => 'product',
            'snapshottable_id' => 42,
            'data' => ['name' => 'Awesome Product', 'price' => 99.99],
        ]);

        $this->assertDatabaseHas('snapshots', [
            'id' => $snapshot->id,
            'type' => 'product',
            'version' => 1,
            'snapshottable_type' => 'product',
            'snapshottable_id' => 42,
        ]);

        $fresh = Snapshot::find($snapshot->id);
        $this->assertInstanceOf(Snapshot::class, $fresh);
        $this->assertEquals(['name' => 'Awesome Product', 'price' => 99.99], $fresh->getRawData());
        $this->assertInstanceOf(DateTimeImmutable::class, $fresh->takenAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $fresh->validUntil());
    }

    #[Test]
    public function valid_until_can_be_null(): void
    {
        $snapshot = Snapshot::create([
            'taken_at' => now()->toImmutable(),
            'type' => 'cart',
            'data' => ['items' => []],
        ]);

        $this->assertNull($snapshot->validUntil());
        $this->assertNull(Snapshot::find($snapshot->id)->validUntil());
    }

    #[Test]
    public function it_takes_the_metadata_and_the_data_from_the_payload_of_the_source(): void
    {
        SnapshotPayloadTypes::register('dummy_v1', DummySnapshotV1::class);
        $takenAt = new DateTimeImmutable('2026-11-04 10:00:00');
        $validUntil = new DateTimeImmutable('2026-11-04 10:15:00');

        $snapshot = Snapshot::take($this->snapshottable(new SnapshotMetadata(1, $takenAt, $validUntil), ['a' => 1]));

        $this->assertEquals('dummy_v1', $snapshot->type);
        $this->assertEquals(1, $snapshot->version());
        $this->assertEquals($takenAt, $snapshot->takenAt());
        $this->assertEquals($validUntil, $snapshot->validUntil());
        $this->assertEquals(['a' => 1], $snapshot->getRawData());
        $this->assertNull($snapshot->snapshottable_type);
    }

    #[Test]
    public function the_payload_can_be_restored_from_a_persisted_snapshot(): void
    {
        SnapshotPayloadTypes::register('dummy_v1', DummySnapshotV1::class);
        $takenAt = new DateTimeImmutable('2026-11-04 10:00:00');
        $validUntil = new DateTimeImmutable('2026-11-04 10:15:00');

        Snapshot::take($this->snapshottable(new SnapshotMetadata(1, $takenAt, $validUntil), ['a' => 1]))->save();

        $payload = Snapshot::first()->payload();
        $this->assertInstanceOf(DummySnapshotV1::class, $payload);
        $this->assertEquals(['a' => 1], $payload->toArray());
        $this->assertEquals(1, $payload->version());
        $this->assertEquals($takenAt->getTimestamp(), $payload->takenAt()->getTimestamp());
        $this->assertEquals($validUntil->getTimestamp(), $payload->validUntil()->getTimestamp());
    }

    #[Test]
    public function proxy_resolves_to_the_snapshot_model(): void
    {
        $this->assertEquals(Snapshot::class, SnapshotProxy::modelClass());
    }

    private function snapshottable(SnapshotMetadata $meta, array $data): Snapshottable
    {
        return new class ($meta, $data) implements Snapshottable {
            public function __construct(private SnapshotMetadata $meta, private array $data)
            {
            }

            public function toSnapshotPayload(): SnapshotPayload
            {
                return new DummySnapshotV1($this->meta, $this->data);
            }

            public static function fromSnapshotPayload(SnapshotPayload $payload): static
            {
                return new static(new SnapshotMetadata($payload->version(), $payload->takenAt()), $payload->toArray());
            }
        };
    }
}
