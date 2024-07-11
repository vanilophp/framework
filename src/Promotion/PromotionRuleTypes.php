<?php

declare(strict_types=1);

namespace Vanilo\Promotion;

use Vanilo\Promotion\Contracts\PromotionRuleType;
use Vanilo\Promotion\Exceptions\InexistentRuleException;

final class PromotionRuleTypes
{
    private static array $registry = [];

    public static function register(string $id, string $class)
    {
        if (array_key_exists($id, self::$registry)) {
            return;
        }

        if (!in_array(PromotionRuleType::class, class_implements($class))) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The class you are trying to register (%s) as promotion rule, ' .
                    'must implement the %s interface.',
                    $class,
                    PromotionRuleType::class
                )
            );
        }

        self::$registry[$id] = $class;
    }

    public static function make(string $id): PromotionRuleType
    {
        $gwClass = self::getClass($id);

        if (null === $gwClass) {
            throw new InexistentRuleException(
                "No rule is registered with the id `$id`."
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
