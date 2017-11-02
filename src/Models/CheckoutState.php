<?php
/**
 * Contains the CheckoutState enum class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-02
 *
 */

namespace Vanilo\Checkout\Models;


use Konekt\Enum\Enum;
use Vanilo\Checkout\Contracts\CheckoutState as CheckoutStateContract;

class CheckoutState extends Enum implements CheckoutStateContract
{
    const __default = self::STARTED;

    const STARTED   = 'started';
    const READY     = 'ready';
    const COMPLETED = 'completed';

    protected static $submittableStates = [self::READY];

    /**
     * @inheritdoc
     */
    public function canBeSubmitted()
    {
        return in_array($this->value, static::$submittableStates);
    }

    /**
     * @inheritdoc
     */
    public static function getSubmittableStates()
    {
        return static::$submittableStates;
    }
}
