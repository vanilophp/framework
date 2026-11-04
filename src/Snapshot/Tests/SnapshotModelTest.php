<?php

declare(strict_types=1);

namespace Vanilo\Snapshot\Tests;

use Carbon\CarbonImmutable;
use DateTimeImmutable;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Snapshot\Contracts\Snapshot as SnapshotContract;
use Vanilo\Snapshot\Models\Snapshot;
use Vanilo\Snapshot\Models\SnapshotProxy;
use Vanilo\Snapshot\SnapshotTypes;

class SnapshotModelTest extends TestCase
{
    #[Test]
    public function it_implements_the_snapshot_interface(): void
    {
        $snapshot = new Snapshot();

        $this->assertInstanceOf(SnapshotContract::class, $snapshot);
    }

    #[Test]
    public function it_can_be_instantiated_via_the_create_factory_method(): void
    {
        $takenAt = new DateTimeImmutable('2026-11-03 12:00:00');
        $validUntil = new DateTimeImmutable('2026-11-03 13:00:00');

        $snapshot = Snapshot::create($takenAt, $validUntil, 2);

        $this->assertInstanceOf(Snapshot::class, $snapshot);
        $this->assertEquals($takenAt, $snapshot->takenAt());
        $this->assertEquals($validUntil, $snapshot->validUntil());
        $this->assertEquals(2, $snapshot->version());
    }

    #[Test]
    public function default_version_is_one(): void
    {
        $takenAt = now();
        $snapshot = Snapshot::create($takenAt, $takenAt);

        $this->assertEquals(1, $snapshot->version());
    }

    #[Test]
    public function taken_at_and_valid_until_return_datetime_immutable_instances(): void
    {
        $takenAt = Carbon::parse('2026-11-03 10:00:00');
        $validUntil = Carbon::parse('2026-11-03 11:00:00');

        $snapshot = Snapshot::create($takenAt, $validUntil);

        $this->assertInstanceOf(DateTimeImmutable::class, $snapshot->takenAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $snapshot->validUntil());
        $this->assertTrue(CarbonImmutable::instance($takenAt)->eq($snapshot->takenAt()));
        $this->assertTrue(CarbonImmutable::instance($validUntil)->eq($snapshot->validUntil()));
    }

    #[Test]
    public function data_returns_empty_array_when_not_set(): void
    {
        $snapshot = new Snapshot();

        $this->assertSame([], $snapshot->getData());
    }

    #[Test]
    public function data_can_be_loaded(): void
    {
        $snapshot = new Snapshot();
        $payload = ['sku' => 'PROD-1', 'price' => 19.99];

        $snapshot->load($payload);

        $this->assertSame($payload, $snapshot->getData());
        $this->assertSame($payload, $snapshot->data);
    }

    #[Test]
    public function it_can_be_persisted_to_the_database(): void
    {
        $snapshot = Snapshot::create(
            now()->toImmutable(),
            now()->addHour()->toImmutable(),
            1
        );
        $snapshot->type = 'product';
        $snapshot->model_type = 'product';
        $snapshot->model_id = 42;
        $snapshot->load(['name' => 'Awesome Product', 'price' => 99.99]);
        $snapshot->save();

        $this->assertDatabaseHas('snapshots', [
            'id' => $snapshot->id,
            'type' => 'product',
            'version' => 1,
            'model_type' => 'product',
            'model_id' => 42,
        ]);

        $fresh = Snapshot::find($snapshot->id);
        $this->assertInstanceOf(Snapshot::class, $fresh);
        $this->assertEquals(['name' => 'Awesome Product', 'price' => 99.99], $fresh->getData());
        $this->assertInstanceOf(DateTimeImmutable::class, $fresh->takenAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $fresh->validUntil());
    }

    #[Test]
    public function proxy_resolves_to_the_snapshot_model(): void
    {
        $this->assertEquals(Snapshot::class, SnapshotProxy::modelClass());
    }

    #[Test]
    public function it_can_be_registered_in_the_snapshot_types_registry(): void
    {
        SnapshotTypes::register('eloquent_snapshot', Snapshot::class);

        $snapshot = SnapshotTypes::make('eloquent_snapshot', [
            'taken_at' => '2026-11-03 14:00:00',
            'valid_until' => '2026-11-03 15:00:00',
            'version' => 3,
            'data' => ['key' => 'value'],
        ]);

        $this->assertInstanceOf(Snapshot::class, $snapshot);
        $this->assertEquals(3, $snapshot->version());
        $this->assertEquals(['key' => 'value'], $snapshot->getData());
        $this->assertInstanceOf(DateTimeImmutable::class, $snapshot->takenAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $snapshot->validUntil());
    }
}
