<?php
/**
 * Contains the PaymentGateways class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-12-26
 *
 */

namespace Vanilo\Payment;

use Vanilo\Payment\Contracts\PaymentGateway;
use Vanilo\Payment\Exceptions\InexistentPaymentGatewayException;

final class PaymentGateways
{
    /** @var array */
    private static $registry = [];

    public static function register(string $id, string $class)
    {
        if (array_key_exists($id, self::$registry)) {
            return;
        }

        if (!class_implements($class, PaymentGateway::class)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The class you are trying to register (%s) as property type, ' .
                    'must implement the %s interface.',
                    $class,
                    PaymentGateway::class
                )
            );
        }

        self::$registry[$id] = $class;
    }

    public static function make(string $id): PaymentGateway
    {
        $gwClass = self::getClass($id);

        if (null === $gwClass) {
            throw new InexistentPaymentGatewayException(
                "No payment gateway is registered with the id `$id`."
            );
        }

        return app()->make($gwClass);
    }

    public static function reset(): void
    {
        self::$registry = [];
    }

    public static function getClass(string $id): ?string
    {
        return self::$registry[$id] ?? null;
    }

    public static function ids(): array
    {
        return array_keys(self::$registry);
    }

    public static function choices(): array
    {
        $result = [];

        foreach (self::$registry as $type => $class) {
            $result[$type] = $class::getName();
        }

        return $result;
    }
}
