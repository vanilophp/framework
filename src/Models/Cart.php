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

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Vanilo\Contracts\Buyable;
use Vanilo\Cart\Contracts\Cart as CartContract;

class Cart extends Model implements CartContract
{
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(CartItemProxy::modelClass(), 'cart_id', 'id');
    }

    /**
     * @inheritDoc
     */
    public function getItems(): Collection
    {
        return $this->items;
    }


    /**
     * @inheritDoc
     */
    public function itemCount()
    {
        return $this->items->sum('quantity');
    }

    /**
     * @inheritDoc
     */
    public function addItem(Buyable $product, $qty = 1, $params = []): \Vanilo\Cart\Contracts\CartItem
    {
        $item = $this->items()->ofCart($this)->byProduct($product)->first();

        if ($item) {
            $item->quantity += $qty;
            $item->save();
        } else {
            $item = $this->items()->create([
                'product_type' => $product->morphTypeName(),
                'product_id'   => $product->getId(),
                'quantity'     => $qty,
                'price'        => $product->getPrice()
            ]);
        }

        $this->load('items');

        return $item;
    }

    /**
     * @inheritDoc
     */
    public function removeItem($item)
    {
        if ($item) {
            $item->delete();
        }

        $this->load('items');
    }

    /**
     * @inheritDoc
     */
    public function removeProduct(Buyable $product)
    {
        $item = $this->items()->ofCart($this)->byProduct($product)->first();

        $this->removeItem($item);
    }

    /**
     * @inheritDoc
     */
    public function clear()
    {
        $this->items()->ofCart($this)->delete();

        $this->load('items');
    }

    /**
     * @inheritDoc
     */
    public function total()
    {
        return $this->items->sum('total');
    }

    /**
     * The cart's user relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    /**
     * @inheritDoc
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @inheritDoc
     */
    public function setUser($user)
    {
        if ($user instanceof Authenticatable) {
            $user = $user->id;
        }

        $this->user_id = $user;
    }
}
