<?php

declare(strict_types=1);

namespace Vanilo\Support\Tests;

use Illuminate\Support\Carbon;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Vanilo\Support\SnapshotTypes;
use Vanilo\Support\Tests\Dummies\DummySnapshotV1;
use Vanilo\Support\Tests\Dummies\DummySnapshotVersionable;


class SnapshotRegistryTest extends TestCase
{
    #[Test]
    public function classes_can_be_registered()
    {
        SnapshotTypes::register('dummy_v1', DummySnapshotV1::class);

        $this->assertEquals(DummySnapshotV1::class, SnapshotTypes::getClassOf('dummy_v1'));
    }

    #[Test]
    public function snapshot_instances_can_be_made_via_the_registry_from_data_arrays()
    {
        SnapshotTypes::register('dummy_v1', DummySnapshotV1::class);

        $rawArray = [
            'taken_at' => '2026-10-27 11:51:09',
            'valid_until' => '2026-10-27 12:06:09',
            'data' => ['hey' => 'hello']
        ];

        $snapshot = SnapshotTypes::make('dummy_v1', $rawArray);
        $this->assertInstanceOf(DummySnapshotV1::class, $snapshot);
        $this->assertTrue(Carbon::make($rawArray['taken_at'])->eq($snapshot->takenAt()));
        $this->assertTrue(Carbon::make($rawArray['valid_until'])->eq($snapshot->validUntil()));
        $this->assertEquals($rawArray['data'], $snapshot->data());
        $this->assertEquals(1, $snapshot->version());
    }

    #[Test]
    public function snapshot_version_is_handled_if_passed()
    {
        SnapshotTypes::register('dummy_versionable', DummySnapshotVersionable::class);

        $dataV2 = [
            'taken_at' => now(),
            'valid_until' => now()->addMinutes(15),
            'data' => ['yo' => 'hey'],
            'version' => 2,
        ];

        $dataV3 = [
            'taken_at' => now(),
            'valid_until' => now()->addMinutes(15),
            'data' => ['yo' => 'hey'],
            'version' => 3,
        ];

        $snapshot2 = SnapshotTypes::make('dummy_versionable', $dataV2);
        $snapshot3 = SnapshotTypes::make('dummy_versionable', $dataV3);
        $this->assertInstanceOf(DummySnapshotVersionable::class, $snapshot2);
        $this->assertInstanceOf(DummySnapshotVersionable::class, $snapshot3);
        $this->assertTrue(Carbon::make($dataV2['taken_at'])->eq($snapshot2->takenAt()));
        $this->assertTrue(Carbon::make($dataV3['taken_at'])->eq($snapshot3->takenAt()));
        $this->assertTrue(Carbon::make($dataV2['valid_until'])->eq($snapshot2->validUntil()));
        $this->assertTrue(Carbon::make($dataV3['valid_until'])->eq($snapshot3->validUntil()));
        $this->assertEquals($dataV2['data'], $snapshot2->data());
        $this->assertEquals($dataV3['data'], $snapshot3->data());
        $this->assertEquals(2, $snapshot2->version());
        $this->assertEquals(3, $snapshot3->version());
    }

    #[Test]
    public function snapshot_data_is_optional_when_creating_via_the_registry()
    {
        SnapshotTypes::register('dummy_v1', DummySnapshotV1::class);

        $rawArray = [
            'taken_at' => now(),
            'valid_until' => now()->addHour(),
        ];

        $snapshot = SnapshotTypes::make('dummy_v1', $rawArray);
        $this->assertInstanceOf(DummySnapshotV1::class, $snapshot);
        $this->assertTrue(Carbon::make($rawArray['taken_at'])->eq($snapshot->takenAt()));
        $this->assertTrue(Carbon::make($rawArray['valid_until'])->eq($snapshot->validUntil()));
        $this->assertEquals([], $snapshot->data());
    }

    #[Test]
    public function taken_at_parameter_is_sufficient_to_make_a_snapshot()
    {
        SnapshotTypes::register('dummy_v1', DummySnapshotV1::class);

        $rawArray = [
            'taken_at' => Carbon::make('1848-03-15 08:00:12', 'Europe/Budapest'),
        ];

        $snapshot = SnapshotTypes::make('dummy_v1', $rawArray);
        $this->assertInstanceOf(DummySnapshotV1::class, $snapshot);
        $this->assertTrue($rawArray['taken_at']->eq($snapshot->takenAt()));
    }

    #[Test]
    public function when_valid_until_is_omitted_it_defaults_to_taken_at()
    {
        SnapshotTypes::register('dummy_v1', DummySnapshotV1::class);

        $rawArray = [
            'taken_at' => Carbon::make('1956-10-23 15:00:23', 'Europe/Budapest'),
        ];

        $snapshot = SnapshotTypes::make('dummy_v1', $rawArray);
        $this->assertInstanceOf(DummySnapshotV1::class, $snapshot);
        $this->assertTrue($rawArray['taken_at']->eq($snapshot->takenAt()));
        $this->assertTrue($rawArray['taken_at']->eq($snapshot->validUntil()));
    }

    #[Test]
    public function it_throws_an_exception_if_no_taken_at_is_passed()
    {
        SnapshotTypes::register('dummy_v1', DummySnapshotV1::class);
        $this->expectException(InvalidArgumentException::class);
        SnapshotTypes::make('dummy_v1');
    }
}
