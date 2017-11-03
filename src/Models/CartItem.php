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

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Vanilo\Contracts\Buyable;
use Vanilo\Cart\Contracts\CartItem as CartItemContract;

/**
 * @property Buyable $product
 * @property float   $total
 */
class CartItem extends Model implements CartItemContract
{
    protected $fillable = ['cart_id','product_type', 'product_id', 'quantity', 'price'];

    public function product()
    {
        return $this->morphTo();
    }

    /**
     * @inheritDoc
     */
    public function getBuyable(): Buyable
    {
        return $this->product;
    }

    /**
     * @inheritDoc
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @inheritDoc
     */
    public function total()
    {
        return $this->price * $this->quantity;
    }

    /**
     * Property accessor alias to the total() method
     *
     * @return float
     */
    public function getTotalAttribute()
    {
        return $this->total();
    }


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
     * @param Builder $query
     * @param Buyable $product
     *
     * @return Builder
     */
    public function scopeByProduct($query, Buyable $product)
    {
        return $query->where([
            ['product_id', '=', $product->getId()],
            ['product_type', '=', $product->morphTypeName()]
        ]);
    }
}
