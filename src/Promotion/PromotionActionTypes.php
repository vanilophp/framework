<?php

declare(strict_types=1);

namespace Vanilo\Promotion;

use Vanilo\Promotion\Contracts\PromotionActionType;
use Vanilo\Promotion\Exceptions\InexistentPromotionActionException;

final class PromotionActionTypes
{
    private static array $registry = [];

    public static function register(string $id, string $class)
    {
        if (array_key_exists($id, self::$registry)) {
            return;
        }

        if (!in_array(PromotionActionType::class, class_implements($class))) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The class you are trying to register (%s) as promotion action, ' .
                    'must implement the %s interface.',
                    $class,
                    PromotionActionType::class
                )
            );
        }

        self::$registry[$id] = $class;
    }

    public static function make(string $id): PromotionActionType
    {
        $gwClass = self::getClass($id);

        if (null === $gwClass) {
            throw new InexistentPromotionActionException(
                "No action is registered with the id `$id`."
            );
        }

        return app()->make($gwClass);
    }

    public static function reset(): void
    {
        self::$registry = [];
    }

    public static function getClass(string $id): ?string
    {
        return self::$registry[$id] ?? null;
    }

    public static function ids(): array
    {
        return array_keys(self::$registry);
    }

    public static function choices(): array
    {
        $result = [];

        foreach (self::$registry as $type => $class) {
            $result[$type] = $class::getName();
        }

        return $result;
    }
}
