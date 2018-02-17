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

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Vanilo\Contracts\Buyable;
use Vanilo\Cart\Contracts\CartItem;
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
    public function getItems(): Collection
    {
        return $this->exists() ? $this->model()->getItems() : collect();
    }


    /**
     * @inheritDoc
     */
    public function addItem(Buyable $product, $qty = 1, $params = []): CartItem
    {
        $cart = $this->findOrCreateCart();

        return $cart->addItem($product, $qty, $params);
    }

    /**
     * @inheritDoc
     */
    public function removeItem($item)
    {
        if ($cart = $this->model()) {
            $cart->removeItem($item);
        }
    }

    /**
     * @inheritDoc
     */
    public function removeProduct(Buyable $product)
    {
        if ($cart = $this->model()) {
            $cart->removeProduct($product);
        }
    }

    /**
     * @inheritDoc
     */
    public function clear()
    {
        if ($cart = $this->model()) {
            $cart->clear();
        }
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
    public function total()
    {
        return $this->exists() ? $this->model()->total() : 0;
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
     * @inheritDoc
     */
    public function isEmpty()
    {
        return $this->itemCount() == 0;
    }

    /**
     * @inheritDoc
     */
    public function isNotEmpty()
    {
        return !$this->isEmpty();
    }

    /**
     * @inheritDoc
     */
    public function destroy()
    {
        $this->clear();

        $this->model()->delete();
        $this->cart = null;

        session()->forget($this->sessionKey);
    }

    /**
     * @inheritdoc
     */
    public function create($forceCreateIfExists = false)
    {
        if ($this->exists() && !$forceCreateIfExists) {
            dump('cowardly returning');
            return;
        }

        $this->createCart();
    }

    /**
     * @inheritDoc
     */
    public function getUser()
    {
        return $this->exists() ? $this->model()->getUser() : null;
    }

    /**
     * @inheritDoc
     */
    public function setUser($user)
    {
        if ($this->exists()) {
            $this->cart->setUser($user);
            $this->cart->save();
            $this->cart->load('user');
        }
    }

    /**
     * @inheritdoc
     */
    public function removeUser()
    {
        $this->setUser(null);
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
        if (config('vanilo.cart.auto_assign_user') && Auth::check()) {
            $attributes = [
                'user_id' => Auth::user()->id
            ];
        }

        $this->cart = CartProxy::create($attributes ?? []);

        session([$this->sessionKey => $this->cart->id]);

        return $this->cart;
    }
}
