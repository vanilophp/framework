<?php

declare(strict_types=1);

/**
 * Contains the CartState enum class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-10-15
 *
 */

namespace Vanilo\Cart\Models;

use Konekt\Enum\Enum;
use Vanilo\Cart\Contracts\CartState as CartStateContract;

class CartState extends Enum implements CartStateContract
{
    public const __DEFAULT = self::ACTIVE;
    public const ACTIVE = 'active';
    public const CHECKOUT = 'checkout';
    public const COMPLETED = 'completed';
    public const ABANDONDED = 'abandoned';

    protected static $labels = [];

    protected static $activeStates = [self::ACTIVE, self::CHECKOUT];

    /**
     * @inheritDoc
     */
    public function isActive(): bool
    {
        return in_array($this->value, static::$activeStates);
    }

    /**
     * @inheritDoc
     */
    public static function getActiveStates(): array
    {
        return static::$activeStates;
    }

    protected static function boot()
    {
        static::$labels = [
            self::ACTIVE => __('Active'),
            self::CHECKOUT => __('Checkout'),
            self::COMPLETED => __('Completed'),
            self::ABANDONDED => __('Abandoned')
        ];
    }
}
