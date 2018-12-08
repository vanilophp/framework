<?php
/**
 * Contains the AttributeValue class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Attributes\Models;

use Illuminate\Database\Eloquent\Model;
use Vanilo\Attributes\Contracts\AttributeValue as AttributeValueContract;

class AttributeValue extends Model implements AttributeValueContract
{
    protected $table = 'attribute_values';

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
