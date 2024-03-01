<?php

declare(strict_types=1);

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

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Vanilo\Cart\Contracts\Cart as CartContract;
use Vanilo\Cart\Contracts\CartItem;
use Vanilo\Cart\Contracts\CartManager as CartManagerContract;
use Vanilo\Cart\Events\CartCreated;
use Vanilo\Cart\Events\CartDeleted;
use Vanilo\Cart\Events\CartDeleting;
use Vanilo\Cart\Events\CartUpdated;
use Vanilo\Cart\Exceptions\InvalidCartConfigurationException;
use Vanilo\Cart\Models\Cart;
use Vanilo\Cart\Models\CartProxy;
use Vanilo\Contracts\Buyable;

class CartManager implements CartManagerContract
{
    public const CONFIG_SESSION_KEY = 'vanilo.cart.session_key';

    /** The key in session that holds the cart id */
    protected ?string $sessionKey;

    protected null|Cart|CartContract $cart = null;

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

    public function __call(string $name, array $arguments): mixed
    {
        if (null !== $this->model()) {
            return call_user_func([$this->model(), $name], ...$arguments);
        }

        return null;
    }

    public function getItems(): Collection
    {
        return $this->exists() ? $this->model()->getItems() : collect();
    }

    public function itemsTotal(): float
    {
        return $this->exists() ? $this->model()->itemsTotal() : 0;
    }

    public function addItem(Buyable $product, int|float $qty = 1, array $params = []): CartItem
    {
        $cart = $this->findOrCreateCart();

        $result = $cart->addItem($product, $qty, $params);
        $this->triggerCartUpdatedEvent();

        return $result;
    }

    public function removeItem(CartItem $item): void
    {
        if ($cart = $this->model()) {
            $cart->removeItem($item);
            $this->triggerCartUpdatedEvent();
        }
    }

    public function removeProduct(Buyable $product): void
    {
        if ($cart = $this->model()) {
            $cart->removeProduct($product);
            $this->triggerCartUpdatedEvent();
        }
    }

    public function clear(): void
    {
        if ($cart = $this->model()) {
            $cart->clear();
            $this->triggerCartUpdatedEvent();
        }
    }

    public function itemCount(): int
    {
        return $this->exists() ? $this->model()->itemCount() : 0;
    }

    public function total(): float
    {
        return $this->exists() ? $this->model()->total() : 0;
    }

    public function exists(): bool
    {
        return (bool) $this->getCartId() && $this->model();
    }

    public function doesNotExist(): bool
    {
        return !$this->exists();
    }

    public function model(): ?CartContract
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

    public function isEmpty(): bool
    {
        return 0 == $this->itemCount();
    }

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    public function destroy(): void
    {
        if ($this->exists()) {
            Event::dispatch(new CartDeleting($this->model()));
        }

        $this->clear();
        $this->model()->delete();
        $this->forget();

        Event::dispatch(new CartDeleted());
    }

    public function create(bool $forceCreateIfExists = false): void
    {
        if ($this->exists() && !$forceCreateIfExists) {
            return;
        }

        $this->createCart();
    }

    public function getUser(): ?Authenticatable
    {
        return $this->exists() ? $this->model()->getUser() : null;
    }

    public function setUser(Authenticatable|int|string|null $user): void
    {
        if ($this->exists()) {
            $this->cart->setUser($user);
            $this->cart->save();
            $this->cart->load('user');
        }
    }

    public function removeUser(): void
    {
        $this->setUser(null);
    }

    public function restoreLastActiveCart($user): void
    {
        $lastActiveCart = CartProxy::ofUser($user)->actives()->latest()->first();

        if ($lastActiveCart) {
            $this->setCartModel($lastActiveCart);
            $this->triggerCartUpdatedEvent();
        }
    }

    public function mergeLastActiveCartWithSessionCart($user): void
    {
        /** @var Cart $lastActiveCart */
        if ($lastActiveCart = CartProxy::ofUser($user)->actives()->latest()->first()) {
            /** @var CartItem $item */
            foreach ($lastActiveCart->getItems() as $item) {
                $this->addItem($item->getBuyable(), $item->getQuantity());
            }

            $lastActiveCart->delete();
            $this->triggerCartUpdatedEvent();
        }
    }

    public function forget(): void
    {
        $this->cart = null;
        session()->forget($this->sessionKey);
    }

    /**
     * Refreshes the underlying cart model from the database
     */
    public function fresh(): self
    {
        if (null !== $this->cart) {
            $this->cart = $this->cart->fresh();
        }

        return $this;
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
     */
    protected function findOrCreateCart(): Cart|CartContract
    {
        return $this->model() ?: $this->createCart();
    }

    /**
     * Creates a new cart model and saves its id in the session
     */
    protected function createCart(): Cart|CartContract
    {
        if (config('vanilo.cart.auto_assign_user') && Auth::check()) {
            $attributes = [
                'user_id' => Auth::user()->id
            ];
        }

        $model = $this->setCartModel(CartProxy::create($attributes ?? []));

        Event::dispatch(new CartCreated($model));

        return $model;
    }

    protected function setCartModel(CartContract $cart): CartContract
    {
        $this->cart = $cart;

        session([$this->sessionKey => $this->cart->id]);

        return $this->cart;
    }

    protected function triggerCartUpdatedEvent(): void
    {
        if ($this->exists()) {
            Event::dispatch(new CartUpdated($this->model()));
        }
    }
}
