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
        self::add($id, $class);
    }

    public static function make(string $id, array $parameters = []): PromotionActionType
    {
        $gwClass = self::getClassOf($id);

        if (null === $gwClass) {
            throw new InexistentPromotionActionException(
                "No action is registered with the id `$id`."
            );
        }

        return app()->make($gwClass);
    }
}
