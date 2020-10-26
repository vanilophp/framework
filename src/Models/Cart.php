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
use Konekt\Enum\Eloquent\CastsEnums;
use Vanilo\Cart\Exceptions\InvalidCartConfigurationException;
use Vanilo\Contracts\Buyable;
use Vanilo\Cart\Contracts\Cart as CartContract;

class Cart extends Model implements CartContract
{
    use CastsEnums;

    const EXTRA_PRODUCT_MERGE_ATTRIBUTES_CONFIG_KEY = 'vanilo.cart.extra_product_attributes';

    protected $guarded = ['id'];

    protected $enums = [
        'state' => 'CartStateProxy@enumClass'
    ];

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
    public function total(): float
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

    public function scopeActives($query)
    {
        return $query->whereIn('state', CartStateProxy::getActiveStates());
    }

    public function scopeOfUser($query, $user)
    {
        return $query->where('user_id', is_object($user) ? $user->id : $user);
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
            'product_id'   => $product->getId(),
            'quantity'     => $qty,
            'price'        => $product->getPrice()
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
        $cfg    = config(self::EXTRA_PRODUCT_MERGE_ATTRIBUTES_CONFIG_KEY, []);

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
