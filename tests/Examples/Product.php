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

namespace Vanilo\Attributes\Tests\Examples;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Vanilo\Attributes\Models\AttributeValueProxy;

class Product extends Model
{
    protected $guarded = ['id'];

    public function attributeValues(): MorphToMany
    {
        return $this->morphToMany(AttributeValueProxy::modelClass(), 'model', 'model_attribute_values', 'model_id', 'attribute_value_id');
    }
}
