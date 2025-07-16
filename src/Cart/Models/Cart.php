<?php

declare(strict_types=1);

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
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Konekt\Enum\Eloquent\CastsEnums;
use Vanilo\Cart\Contracts\Cart as CartContract;
use Vanilo\Cart\Contracts\CartItem as CartItemContract;
use Vanilo\Cart\Exceptions\InvalidCartConfigurationException;
use Vanilo\Contracts\Buyable;

class Cart extends Model implements CartContract
{
    use CastsEnums;

    public const EXTRA_PRODUCT_MERGE_ATTRIBUTES_CONFIG_KEY = 'vanilo.cart.extra_product_attributes';

    protected $guarded = ['id'];

    protected $enums = [
        'state' => 'CartStateProxy@enumClass',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(CartItemProxy::modelClass(), 'cart_id', 'id');
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function itemCount(): int
    {
        return (int)$this->items->sum('quantity');
    }

    public function addItem(Buyable $product, int|float $qty = 1, array $params = [], bool $forceNewItem = false): CartItemContract
    {
        $item = match ($forceNewItem) {
            false => $this->resolveCartItem($product, $params),
            default => null,
        };

        if (null !== $item) {
            $item->quantity += $qty;
            $item->save();
        } else {
            $item = $this->items()->create(
                array_merge(
                    $this->getDefaultCartItemAttributes($product, $qty),
                    $this->getExtraProductMergeAttributes($product),
                    $params['attributes'] ?? []
                )
            );
        }

        $this->load('items');

        return $item;
    }

    public function addSubItem(CartItemContract $parent, Buyable $product, float|int $qty = 1, array $params = []): CartItemContract
    {
        $params = array_merge($params, ['attributes' => ['parent_id' => $parent->id]]);

        $result = $this->addItem($product, $qty, $params);
        if ($parent->relationLoaded('children')) {
            $parent->unsetRelation('children');
        }

        return $result;
    }

    public function removeItem(CartItemContract $item): void
    {
        if ($item instanceof Model) {
            $item->delete();
        }

        $this->load('items');
    }

    /**
     * @inheritDoc
     */
    public function removeProduct(Buyable $product): void
    {
        $item = $this->items()->ofCart($this)->byProduct($product)->first();
        if ($item) {
            $this->removeItem($item);
        }
    }

    /**
     * @inheritDoc
     */
    public function clear(): void
    {
        $this->items()->ofCart($this)->delete();

        $this->load('items');
    }

    public function total(): float
    {
        return $this->items->sum('total');
    }

    public function itemsTotal(): float
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
        $userModel = config('vanilo.cart.user.model') ?: config('auth.providers.users.model');

        return $this->belongsTo($userModel);
    }

    public function getUser(): ?Authenticatable
    {
        return $this->user;
    }

    public function setUser(Authenticatable|int|string|null $user): void
    {
        if ($user instanceof Authenticatable) {
            $user = $user->id;
        }

        $this->user_id = $user;
    }

    public function scopeActives($query)
    {
        return $query->whereIn('state', CartStateProxy::getActiveStates());
    }

    public function scopeOfUser($query, $user)
    {
        return $query->where('user_id', is_object($user) ? $user->id : $user);
    }

    protected function resolveCartItem(Buyable $buyable, array $parameters): ?CartItemContract
    {
        /** @var Collection $existingCartItems */
        $existingCartItems = $this->items()->ofCart($this)->byProduct($buyable)->get();
        if ($existingCartItems->isEmpty()) {
            return null;
        }

        $itemConfig = Arr::get($parameters, 'attributes.configuration');
        $parentId = Arr::get($parameters, 'attributes.parent_id');

        if (1 === $existingCartItems->count()) {
            $item = $this->items()->ofCart($this)->byProduct($buyable)->first();

            if ($this->configurationsMatch($item->configuration(), $itemConfig) && $this->parentIdMatches($parentId, $item)) {
                return $item;
            }

            return null;
        }

        foreach ($existingCartItems as $item) {
            if ($this->configurationsMatch($item->configuration(), $itemConfig) && $this->parentIdMatches($parentId, $item)) {
                return $item;
            }
        }

        return null;
    }

    protected function configurationsMatch(?array $config1, ?array $config2): bool
    {
        if (empty($config1) && empty($config2)) {
            return true;
        } elseif (empty($config1) && !empty($config2)) {
            return false;
        } elseif (empty($config2) && !empty($config1)) {
            return false;
        }

        if (array_is_list($config1)) {
            if (!array_is_list($config2)) {
                return false;
            }

            return empty(array_diff($config1, $config2)) && empty(array_diff($config2, $config1));
        } else { //Config 1 is associative
            if (array_is_list($config2)) {
                return false;
            }

            return empty(array_diff_assoc($config1, $config2)) && empty(array_diff_assoc($config2, $config1));
        }

        return false;
    }

    protected function parentIdMatches(mixed $parentId, CartItemContract $item): bool
    {
        if (null === $parentId && null === $item->parent_id) {
            return true;
        }

        return intval($parentId) === $item->parent_id;
    }

    /**
     * Returns the default attributes of a Buyable for a cart item
     *
     * @param Buyable $product
     * @param integer $qty
     *
     * @return array
     */
    protected function getDefaultCartItemAttributes(Buyable $product, $qty)
    {
        return [
            'product_type' => $product->morphTypeName(),
            'product_id' => $product->getId(),
            'quantity' => $qty,
            'price' => $product->getPrice(),
        ];
    }

    /**
     * Returns the extra product merge attributes for cart_items based on the config
     *
     * @param Buyable $product
     *
     * @throws InvalidCartConfigurationException
     *
     * @return array
     */
    protected function getExtraProductMergeAttributes(Buyable $product)
    {
        $result = [];
        $cfg = config(self::EXTRA_PRODUCT_MERGE_ATTRIBUTES_CONFIG_KEY, []);

        if (!is_array($cfg)) {
            throw new InvalidCartConfigurationException(
                sprintf(
                    'The value of `%s` configuration must be an array',
                    self::EXTRA_PRODUCT_MERGE_ATTRIBUTES_CONFIG_KEY
                )
            );
        }

        foreach ($cfg as $attribute) {
            if (!is_string($attribute)) {
                throw new InvalidCartConfigurationException(
                    sprintf(
                        'The configuration `%s` can only contain an array of strings, `%s` given',
                        self::EXTRA_PRODUCT_MERGE_ATTRIBUTES_CONFIG_KEY,
                        gettype($attribute)
                    )
                );
            }

            $result[$attribute] = $product->{$attribute};
        }

        return $result;
    }
}
