<?php

declare(strict_types=1);

namespace Vanilo\Support;

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

    public static function make(string $id, array $parameters = []): Snapshot
    {
        $class = self::getClassOf($id);

        if (null === $class) {
            throw new UnknownSnapshotTypeException("No snapshot type is registered with the id `$id`.");
        }

        static::validate($class);

        return $class::from($parameters);
    }
}
