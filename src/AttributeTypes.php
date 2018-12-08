<?php
/**
 * Contains the AttributeTypes class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Attributes;

use Vanilo\Attributes\Contracts\AttributeType;
use Vanilo\Attributes\Types\Boolean;
use Vanilo\Attributes\Types\Integer;
use Vanilo\Attributes\Types\Number;
use Vanilo\Attributes\Types\Text;

class AttributeTypes
{
    private const BUILT_IN_TYPES = [
        'text'    => Text::class,
        'boolean' => Boolean::class,
        'integer' => Integer::class,
        'number'  => Number::class
    ];

    private static $registry = self::BUILT_IN_TYPES;

    public static function register(string $type, string $class)
    {
        if (array_key_exists($type, self::$registry)) {
            return;
        }

        if (!class_implements($class, AttributeType::class)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The class you are trying to register (%s) as attribute type, ' .
                    'must implement the %s interface.',
                    $class,
                    AttributeType::class
                )
            );
        }

        self::$registry[$type] = $class;
    }

    public static function getClass(string $type): ?string
    {
        return self::$registry[$type] ?? null;
    }

    public static function getType(string $class): ?string
    {
        foreach (self::$registry as $type => $className) {
            if ($class === $className) {
                return $type;
            }
        }

        return null;
    }
}
