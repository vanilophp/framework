<?php
/**
 * Contains the Attribute class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Attributes\Models;

use Illuminate\Database\Eloquent\Model;
use Vanilo\Attributes\Contracts\Attribute as AttributeContract;

class Attribute extends Model implements AttributeContract
{
    protected $table = 'attributes';

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
