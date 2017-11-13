<?php
/**
 * Contains the GigaBrutthewCart test class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-13
 *
 */


namespace Vanilo\Checkout\Tests\Mocks;


use Illuminate\Support\Collection;
use Vanilo\Contracts\Buyable;
use Vanilo\Contracts\CheckoutSubject;

class Cart implements CheckoutSubject
{
    /** @var  Collection */
    protected $items;

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        $this->items = collect();
    }


    /**
     * @param Buyable $product
     * @param int     $qty
     *
     * @return CartItem
     */
    public function addItem(Buyable $product, $qty = 1)
    {
        // Fetch existing item from cart (if there is)
        $item = $this->items->first(function($item) use ($product) {
            return $item->getBuyable()->getId() == $product->getId();
        });

        if ($item) {
            $item->increaseQuantityWith($qty);
        } else {
            $item = new CartItem($product, $qty);
            $this->items->push($item);
        }

        return $item;
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
    public function total()
    {
        return $this->items->sum->total();
    }
}
