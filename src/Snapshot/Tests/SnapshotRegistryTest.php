<?php

declare(strict_types=1);

namespace Vanilo\Snapshot\Tests;

use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Vanilo\Snapshot\SnapshotMetadata;
use Vanilo\Snapshot\SnapshotPayloadTypes;
use Vanilo\Snapshot\Tests\Dummies\DummySnapshotV1;
use Vanilo\Snapshot\Tests\Dummies\DummySnapshotVersionable;

class SnapshotRegistryTest extends TestCase
{
    #[Test]
    public function classes_can_be_registered()
    {
        SnapshotPayloadTypes::register('dummy_v1', DummySnapshotV1::class);

        $this->assertEquals(DummySnapshotV1::class, SnapshotPayloadTypes::getClassOf('dummy_v1'));
    }

    #[Test]
    public function payload_instances_can_be_made_via_the_registry_from_data_arrays()
    {
        SnapshotPayloadTypes::register('dummy_v1', DummySnapshotV1::class);

        $takenAt = new DateTimeImmutable('2026-10-27 11:51:09');
        $validUntil = new DateTimeImmutable('2026-10-27 12:06:09');
        $rawArray = [
            'data' => ['hey' => 'hello'],
            'meta' => new SnapshotMetadata(1, $takenAt, $validUntil),
        ];

        $payload = SnapshotPayloadTypes::make('dummy_v1', $rawArray);
        $this->assertInstanceOf(DummySnapshotV1::class, $payload);
        $this->assertEquals($rawArray['data'], $payload->toArray());
        $this->assertEquals(1, $payload->version());
        $this->assertEquals($takenAt, $payload->takenAt());
        $this->assertEquals($validUntil, $payload->validUntil());
    }

    #[Test]
    public function payload_version_is_handled_if_passed()
    {
        SnapshotPayloadTypes::register('dummy_versionable', DummySnapshotVersionable::class);

        $dataV2 = [
            'data' => ['yo' => 'hey'],
            'meta' => new SnapshotMetadata(2, new DateTimeImmutable()),
        ];

        $dataV3 = [
            'data' => ['yo' => 'hey'],
            'meta' => new SnapshotMetadata(3, new DateTimeImmutable()),
        ];

        $payload2 = SnapshotPayloadTypes::make('dummy_versionable', $dataV2);
        $payload3 = SnapshotPayloadTypes::make('dummy_versionable', $dataV3);
        $this->assertInstanceOf(DummySnapshotVersionable::class, $payload2);
        $this->assertInstanceOf(DummySnapshotVersionable::class, $payload3);
        $this->assertEquals($dataV2['data'], $payload2->toArray());
        $this->assertEquals($dataV3['data'], $payload3->toArray());
        $this->assertEquals(2, $payload2->version());
        $this->assertEquals(3, $payload3->version());
    }

    #[Test]
    public function payload_data_is_optional_when_creating_via_the_registry()
    {
        SnapshotPayloadTypes::register('dummy_v1', DummySnapshotV1::class);

        $payload = SnapshotPayloadTypes::make('dummy_v1', ['meta' => new SnapshotMetadata(1, new DateTimeImmutable())]);
        $this->assertInstanceOf(DummySnapshotV1::class, $payload);
        $this->assertEquals([], $payload->toArray());
    }

    #[Test]
    public function valid_until_is_optional_in_the_metadata()
    {
        SnapshotPayloadTypes::register('dummy_v1', DummySnapshotV1::class);

        $payload = SnapshotPayloadTypes::make('dummy_v1', ['meta' => new SnapshotMetadata(1, new DateTimeImmutable())]);
        $this->assertNull($payload->validUntil());
    }

    #[Test]
    public function it_throws_an_exception_if_no_metadata_is_passed()
    {
        SnapshotPayloadTypes::register('dummy_v1', DummySnapshotV1::class);

        $this->expectException(InvalidArgumentException::class);
        SnapshotPayloadTypes::make('dummy_v1', ['data' => ['hey' => 'hello']]);
    }
}
