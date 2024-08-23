<?php

declare(strict_types=1);

namespace Vanilo\Promotion;

use Konekt\Extend\Concerns\HasRegistry;
use Konekt\Extend\Concerns\RequiresClassOrInterface;
use Konekt\Extend\Contracts\Registry;
use Vanilo\Promotion\Contracts\PromotionActionType;
use Vanilo\Promotion\Exceptions\InexistentPromotionActionException;

final class PromotionActionTypes implements Registry
{
    use HasRegistry;
    use RequiresClassOrInterface;

    private static string $requiredInterface = PromotionActionType::class;

    public static function register(string $id, string $class)
    {
        return self::add($id, $class);
    }

    public static function make(string $id, array $parameters = []): PromotionActionType
    {
        $class = self::getClassOf($id);

        if (null === $class) {
            throw new InexistentPromotionActionException(
                "No action is registered with the id `$id`."
            );
        }

        return app()->make($class, $parameters);
    }
}
