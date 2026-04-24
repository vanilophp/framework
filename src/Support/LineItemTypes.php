<?php

declare(strict_types=1);

namespace Vanilo\Support;

use Illuminate\Support\Facades\App;
use Konekt\Extend\Concerns\HasRegistry;
use Konekt\Extend\Concerns\RequiresClassOrInterface;
use Konekt\Extend\Contracts\Registry;
use Vanilo\Contracts\LineItemType;
use Vanilo\Support\Exceptions\UnknownLineItemTypeException;

class LineItemTypes implements Registry
{
    use HasRegistry;
    use RequiresClassOrInterface;

    private static string $requiredInterface = LineItemType::class;

    public static function register(string $id, string $class)
    {
        return self::add($id, $class);
    }

    public static function make(string $id, array $parameters = []): LineItemType
    {
        $class = self::getClassOf($id);

        if (null === $class) {
            throw new UnknownLineItemTypeException("No line item type is registered with the id `$id`.");
        }

        return App::make($class, $parameters);
    }
}
