<?php

declare(strict_types=1);

/**
 * Contains the PropertyValue interface.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Properties\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface PropertyValue
{
    /**
     * Returns the transformed value according to the underlying type
     */
    public function getCastedValue(): mixed;

    public static function findByPropertyAndValue(string $propertySlug, mixed $value): ?PropertyValue;

    /**
     * @example ['color' => 'blue', 'shape' => 'heart']
     * @param array<string, mixed> $conditions The keys of the entries = the property slug, the values = the scalar property value
     */
    public static function getByScalarPropertiesAndValues(array $conditions): Collection;
}
