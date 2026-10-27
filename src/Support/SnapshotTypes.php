<?php

declare(strict_types=1);

namespace Vanilo\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Konekt\Extend\Concerns\HasRegistry;
use Konekt\Extend\Concerns\RequiresClassOrInterface;
use Konekt\Extend\Contracts\Registry;
use Vanilo\Contracts\Snapshot;
use Vanilo\Support\Exceptions\UnknownSnapshotTypeException;

class SnapshotTypes implements Registry
{
    use HasRegistry;
    use RequiresClassOrInterface;

    private static string $requiredInterface = Snapshot::class;

    public static function register(string $id, string $class)
    {
        return self::add($id, $class);
    }

    /**
     * @param array{taken_at: \DateTimeInterface|string, valid_until?: \DateTimeInterface|string, version?: int, data?: array} $parameters
     */
    public static function make(string $id, array $parameters = []): Snapshot
    {
        $class = self::getClassOf($id);

        if (null === $class) {
            throw new UnknownSnapshotTypeException("No snapshot type is registered with the id `$id`.");
        }

        static::validate($class);
        [$takenAt, $validUntil, $version, $data] = self::normalizeParameters($parameters);

        /** @var Snapshot $instance */
        $instance = match (is_int($version)) {
            true => $class::create(takenAt: $takenAt, validUntil: $validUntil, version: $version),
            default => $class::create(takenAt: $takenAt, validUntil: $validUntil),
        };

        if (is_array($data)) {
            $instance->load($data);
        }

        return $instance;
    }

    /**
     * @return array{\DateTimeInterface, \DateTimeInterface, int|null, array|null}
     * @throws \InvalidArgumentException
     */
    protected static function normalizeParameters(array $input): array
    {
        /** @var \DateTimeInterface $takenAt */
        $takenAt = Carbon::make($input['taken_at'] ?? null);
        if (!$takenAt instanceof \DateTimeInterface) {
            throw new \InvalidArgumentException('The `taken_at` parameter must be an instance of `DateTimeInterface`');
        }

        $validUntil = Carbon::make($input['valid_until'] ?? $takenAt);
        if (!$validUntil instanceof \DateTimeInterface) {
            throw new \InvalidArgumentException('The `valid_until` parameter must be an instance of `DateTimeInterface`');
        }

        $version = $input['version'] ?? null;
        if (null !== $version && !is_int($version)) {
            throw new \InvalidArgumentException('The `version` parameter must be either an integer or absent');
        }

        if (null !== $data = ($input['data'] ?? null)) {
            $data = Arr::wrap($data);
        }

        return [$takenAt, $validUntil, $version, $data];
    }
}
