<?php

declare(strict_types=1);

namespace Vanilo\Promotion;

use Konekt\Extend\Concerns\HasRegistry;
use Konekt\Extend\Concerns\RequiresClassOrInterface;
use Konekt\Extend\Contracts\Registry;
use Vanilo\Promotion\Contracts\PromotionRuleType;
use Vanilo\Promotion\Exceptions\InexistentPromotionRuleException;

final class PromotionRuleTypes implements Registry
{
    use HasRegistry;
    use RequiresClassOrInterface;

    private static string $requiredInterface = PromotionRuleType::class;

    public static function register(string $id, string $class)
    {
        return self::add($id, $class);
    }

    public static function make(string $id, array $parameters = []): PromotionRuleType
    {
        $class = self::getClassOf($id);

        if (null === $class) {
            throw new InexistentPromotionRuleException(
                "No rule is registered with the id `$id`."
            );
        }

        return app()->make($class, $parameters);
    }
}
