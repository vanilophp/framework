<?php
/**
 * Contains the PropertyTypes class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Properties;

use Vanilo\Properties\Contracts\PropertyType;
use Vanilo\Properties\Types\Boolean;
use Vanilo\Properties\Types\Integer;
use Vanilo\Properties\Types\Number;
use Vanilo\Properties\Types\Text;

final class PropertyTypes
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

        if (!class_implements($class, PropertyType::class)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The class you are trying to register (%s) as property type, ' .
                    'must implement the %s interface.',
                    $class,
                    PropertyType::class
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

    public static function values(): array
    {
        return array_keys(self::$registry);
    }

    public static function choices(): array
    {
        $result = [];

        foreach (self::$registry as $type => $class) {
            $result[$type] = (new $class())->getName();
        }

        return $result;
    }
}
