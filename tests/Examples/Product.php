<?php
/**
 * Contains the Product class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Properties\Tests\Examples;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Vanilo\Properties\Models\PropertyValueProxy;

class Product extends Model
{
    protected $guarded = ['id'];

    public function propertyValues(): MorphToMany
    {
        return $this->morphToMany(PropertyValueProxy::modelClass(), 'model',
            'model_property_values', 'model_id', 'property_value_id');
    }
}
