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
    public function itemCount()
    {
        return $this->items->sum('quantity');
    }

    /**
     * @inheritDoc
     */
    public function addItem($product, $qty = 1, $params = [])
    {
        $item = $this->items()->ofCart($this)->byProduct($product)->first();

        if ($item) {
            $item->quantity += $qty;
            $item->save();
        } else {
            $this->items()->create([
                'product_type' => classpath_to_slug(get_class($product)),
                'product_id'   => $product->getId(),
                'quantity'     => $qty,
                'price'        => $product->getPrice()
            ]);
        }

        $this->load('items');
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
    public function removeProduct($product)
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
}
