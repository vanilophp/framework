<?php
/**
 * Contains the Cart class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-28
 *
 */


namespace Vanilo\Cart\Models;


use Illuminate\Database\Eloquent\Model;
use Vanilo\Cart\Contracts\Cart as CartContract;

class Cart extends Model implements CartContract
{
    protected $guarded = ['id'];

}
