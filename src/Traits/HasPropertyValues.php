<?php
/**
 * Contains the HasPropertyValues trait.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-02-03
 *
 */

namespace Vanilo\Properties\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Vanilo\Properties\Contracts\PropertyValue;
use Vanilo\Properties\Models\PropertyValueProxy;

trait HasPropertyValues
{
    public function propertyValues(): MorphToMany
    {
        return $this->morphToMany(
            PropertyValueProxy::modelClass(),
            'model',
            'model_property_values',
            'model_id',
            'property_value_id'
        );
    }

    public function addPropertyValue(PropertyValue $propertyValue): void
    {
        $this->propertyValues()->attach($propertyValue);
    }

    public function addPropertyValues(iterable $propertyValues)
    {
        foreach ($propertyValues as $propertyValue) {
            if (! $propertyValue instanceof PropertyValue) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Every element passed to addPropertyValues must be a PropertyValue object. Given `%s`.',
                        is_object($propertyValue) ? get_class($propertyValue) : gettype($propertyValue)
                    )
                );
            }
        }

        return $this->propertyValues()->saveMany($propertyValues);
    }

    public function removePropertyValue(PropertyValue $propertyValue)
    {
        return $this->propertyValues()->detach($propertyValue);
    }
}
