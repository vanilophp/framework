<?php
/**
 * Contains the CartManager class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-29
 *
 */


namespace Vanilo\Cart;

use Vanilo\Cart\Contracts\CartManager as CartManagerContract;
use Vanilo\Cart\Models\Cart;
use Vanilo\Cart\Models\CartProxy;

class CartManager implements CartManagerContract
{
    /** @var string The key in session that holds the cart id */
    protected $sessionKey;

    /** @var  Cart  The Cart model instance */
    protected $cart;

    public function __construct()
    {
        $this->sessionKey = config('vanilo.cart.session_key');
    }

    /**
     * @inheritDoc
     */
    public function addItem($product, $qty = 1, $params = [])
    {
        $cart = $this->findOrCreateCart();

        $cart->addItem($product, $qty, $params);
    }

    /**
     * @inheritDoc
     */
    public function removeItem($item)
    {
        // TODO: Implement removeItem() method.
    }

    /**
     * @inheritDoc
     */
    public function removeProduct($product)
    {
        // TODO: Implement removeProduct() method.
    }

    /**
     * @inheritDoc
     */
    public function clear()
    {
        // TODO: Implement clear() method.
    }

    /**
     * @inheritDoc
     */
    public function itemCount()
    {
        return $this->exists() ? $this->model()->itemCount() : 0;
    }

    /**
     * @inheritDoc
     */
    public function exists()
    {
        return (bool) $this->getCartId();
    }

    /**
     * @inheritDoc
     */
    public function doesNotExist()
    {
        return !$this->exists();
    }

    /**
     * @inheritDoc
     */
    public function model()
    {
        if ($this->cart) {
            return $this->cart;
        } elseif ($id = $this->getCartId()) {
            $this->cart = CartProxy::find($id);

            return $this->cart;
        }

        return null;
    }

    /**
     * Returns the model id of the cart for the current session
     * or null if it does not exist
     *
     * @return int|null
     */
    protected function getCartId()
    {
        return session($this->sessionKey);
    }

    /**
     * Returns the cart model for the current session by either fetching it or creating one
     *
     * @return Cart
     */
    protected function findOrCreateCart()
    {
        return $this->model() ?: $this->createCart();
    }

    /**
     * Creates a new cart model and saves it's id in the session
     */
    protected function createCart()
    {
        $this->cart = CartProxy::create([]);

        session([$this->sessionKey => $this->cart->id]);

        return $this->cart;
    }
}
