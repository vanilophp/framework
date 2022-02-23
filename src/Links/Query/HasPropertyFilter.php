<?php

declare(strict_types=1);

/**
 * Contains the HasPropertyFilter trait.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-23
 *
 */

namespace Vanilo\Links\Query;

trait HasPropertyFilter
{
    // Since the properties are an optional dependency, we use it very cautiously
    private static string $propertyProxyClass = '\\Vanilo\\Properties\\Models\\PropertyProxy';

    private static ?string $propertiesModelClass = null;

    private null|int|string $property = null;

    public static function usePropertiesModel(string $class): void
    {
        self::$propertiesModelClass = $class;
    }

    public function basedOn(int|string $property): self
    {
        $this->property = $property;

        return $this;
    }

    private function propertyId(): ?int
    {
        return match (true) {
            is_null($this->property) => null,
            is_int($this->property) => $this->property,
            is_string($this->property) => $this->fetchProperty()?->id,
            default => null,
        };
    }

    private function hasPropertyFilter(): bool
    {
        return null !== $this->property;
    }

    /**
     * @throws Exception
     * It only works if the Properties module is installed and loaded by Concord
     */
    private function fetchProperty(): ?object
    {
        if (null !== self::$propertiesModelClass) {
            $propertiesClass = self::$propertiesModelClass;
        } else { // Obtain from Concord
            $proxyClass = self::$propertyProxyClass;
            if (!class_exists($proxyClass)) {
                throw new Exception('The properties module is missing. Use `composer req vanilo/properties` to install it.');
            }
            $propertiesClass = $proxyClass::modelClass();
        }

        return $propertiesClass::findBySlug($this->property);
    }
}
