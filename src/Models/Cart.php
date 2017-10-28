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

    public function addItem($product, $qty = 1, $params = [])
    {
        // TODO: Implement addItem() method.
    }

    public function removeItem($item)
    {
        // TODO: Implement removeItem() method.
    }

    public function removeProduct($product)
    {
        // TODO: Implement removeProduct() method.
    }

    public function clear()
    {
        // TODO: Implement clear() method.
    }

    public function itemCount()
    {
        // TODO: Implement itemCount() method.
    }

    public static function doesExist()
    {
        // TODO: Implement doesExist() method.
    }

    public static function doesNotExist()
    {
        // TODO: Implement doesNotExist() method.
    }


}
