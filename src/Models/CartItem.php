<?php
/**
 * Contains the CartItem class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-29
 *
 */


namespace Vanilo\Cart\Models;

use Illuminate\Database\Eloquent\Model;
use Vanilo\Cart\Contracts\CartItem as CartItemContract;

class CartItem extends Model implements CartItemContract
{
    protected $fillable = ['cart_id','product_type', 'product_id', 'quantity', 'price'];

    /**
     * Scope to query items of a cart
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $cart Cart object or cart id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfCart($query, $cart)
    {
        $cartId = is_object($cart) ? $cart->id : $cart;

        return $query->where('cart_id', $cartId);
    }

    /**
     * Scope to query items by product (Buyable)
     *
     * @param $query
     * @param $product
     */
    public function scopeByProduct($query, $product)
    {
        if (is_object($product)) {
            $productId   = $product->getId();
            $productType = classpath_to_slug(get_class($product));
        } elseif (is_string($product)) {
            $productId   = $product; // should lookup by SKU?
            $productType = 'product';
        } else {
            $productId   = $product;
            $productType = 'product';
        }

        return $query->where([
            ['product_id', '=', $productId],
            ['product_type', '=', $productType]
        ]);
    }
}
