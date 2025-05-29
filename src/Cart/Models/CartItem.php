<?php

declare(strict_types=1);

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
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vanilo\Cart\Contracts\CartItem as CartItemContract;
use Vanilo\Contracts\Buyable;
use Vanilo\Support\Traits\ConfigurableModel;
use Vanilo\Support\Traits\ConfigurationHasNoSchema;

/**
 * @property int $id
 * @property int $cart_id
 * @property int|null $parent_id
 * @property string $product_type
 * @property int $product_id
 * @property float $price
 * @property int $quantity
 * @property array|null $configuration
 * @property-read Buyable $product
 * @property-read float $total
 * @property-read CartItem|null $parent
 */
class CartItem extends Model implements CartItemContract
{
    use ConfigurableModel;
    use ConfigurationHasNoSchema;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'configuration' => 'json',
    ];

    public function product()
    {
        return $this->morphTo();
    }

    public function getBuyable(): Buyable
    {
        return $this->product;
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CartItemProxy::modelClass(), 'parent_id');
    }

    public function hasParent(): bool
    {
        return null !== $this->parent_id;
    }

    public function getParent(): ?CartItemContract
    {
        return $this->parent;
    }

    public function getQuantity(): int
    {
        return (int) $this->quantity;
    }

    public function total(): float
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
