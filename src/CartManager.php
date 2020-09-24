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
use Vanilo\Cart\Contracts\Cart as CartContract;
use Vanilo\Cart\Exceptions\InvalidCartConfigurationException;
use Vanilo\Contracts\Buyable;
use Vanilo\Cart\Contracts\CartItem;
use Vanilo\Cart\Contracts\CartManager as CartManagerContract;
use Vanilo\Cart\Models\Cart;
use Vanilo\Cart\Models\CartProxy;

class CartManager implements CartManagerContract
{
    const CONFIG_SESSION_KEY = 'vanilo.cart.session_key';

    /** @var string The key in session that holds the cart id */
    protected $sessionKey;

    /** @var  Cart  The Cart model instance */
    protected $cart;

    public function __construct()
    {
        $this->sessionKey = config(self::CONFIG_SESSION_KEY);

        if (empty($this->sessionKey)) {
            throw new InvalidCartConfigurationException(
                sprintf(
                    'Cart session key (`%s`) is empty. Please provide a valid value.',
                    self::CONFIG_SESSION_KEY
                )
            );
        }
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
    public function total(): float
    {
        return $this->exists() ? $this->model()->total() : 0;
    }

    /**
     * @inheritDoc
     */
    public function exists()
    {
        return (bool) $this->getCartId() && $this->model();
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
        $id = $this->getCartId();

        if ($id && $this->cart) {
            return $this->cart;
        } elseif ($id) {
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
        return 0 == $this->itemCount();
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
        $this->forget();
    }

    /**
     * @inheritdoc
     */
    public function create($forceCreateIfExists = false)
    {
        if ($this->exists() && !$forceCreateIfExists) {
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

    public function restoreLastActiveCart($user)
    {
        $lastActiveCart = CartProxy::ofUser($user)->actives()->latest()->first();

        if ($lastActiveCart) {
            $this->setCartModel($lastActiveCart);
        }
    }

    public function mergeLastActiveCartWithSessionCart($user)
    {
        /** @var Cart $lastActiveCart */
        if ($lastActiveCart = CartProxy::ofUser($user)->actives()->latest()->first()) {
            /** @var CartItem $item */
            foreach ($lastActiveCart->getItems() as $item) {
                $this->addItem($item->getBuyable(), $item->getQuantity());
            }

            $lastActiveCart->delete();
        }
    }

    /**
     * @inheritDoc
     */
    public function forget()
    {
        $this->cart = null;
        session()->forget($this->sessionKey);
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

        return $this->setCartModel(CartProxy::create($attributes ?? []));
    }

    protected function setCartModel(CartContract $cart): CartContract
    {
        $this->cart = $cart;

        session([$this->sessionKey => $this->cart->id]);

        return $this->cart;
    }
}
