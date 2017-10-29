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


namespace Konekt\Cart\Models;


use Illuminate\Database\Eloquent\Model;
use Konekt\Cart\Contracts\Cart as CartContract;

class Cart extends Model implements CartContract
{
    protected $guarded = ['id'];

}
