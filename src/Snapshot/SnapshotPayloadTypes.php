<?php

declare(strict_types=1);

namespace Vanilo\Snapshot;

use InvalidArgumentException;
use Konekt\Extend\Concerns\HasRegistry;
use Konekt\Extend\Concerns\RequiresClassOrInterface;
use Konekt\Extend\Contracts\Registry;
use Vanilo\Snapshot\Contracts\SnapshotPayload;
use Vanilo\Snapshot\Exceptions\UnknownSnapshotTypeException;

class SnapshotPayloadTypes implements Registry
{
    use HasRegistry;
    use RequiresClassOrInterface;

    private static string $requiredInterface = SnapshotPayload::class;

    public static function register(string $id, string $class)
    {
        return self::add($id, $class);
    }

    /**
     * @param array{meta: SnapshotMetadata, data?: array} $parameters
     */
    public static function make(string $id, array $parameters = []): SnapshotPayload
    {
        $class = self::getClassOf($id)
            ?? throw new UnknownSnapshotTypeException("No snapshot type is registered with the id `$id`.");

        $meta = $parameters['meta'] ?? null;
        if (!$meta instanceof SnapshotMetadata) {
            throw new InvalidArgumentException('The `meta` parameter must be a ' . SnapshotMetadata::class . ' instance.');
        }

        return $class::fromArray($parameters['data'] ?? [], $meta);
    }
}
