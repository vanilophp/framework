<?php
/**
 * Contains the NanoIdGenerator class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-09-25
 *
 */

namespace Vanilo\Order\Generators;

use Vanilo\Order\Contracts\Order;
use Vanilo\Order\Contracts\OrderNumberGenerator;
use Vanilo\Support\Generators\NanoIdGenerator as BaseNanoIdGenerator;

final class NanoIdGenerator extends BaseNanoIdGenerator implements OrderNumberGenerator
{
    private const ALPHABET = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    private $alphabet;

    private $size = 12;

    public function __construct(int $size = null, string $alphabet = null)
    {
        parent::__construct(
            $size ?? $this->config('size', $this->size),
            $alphabet ?? $this->config('alphabet', self::ALPHABET)
        );
    }

    public function generateNumber(Order $order = null): string
    {
        return parent::generate();
    }

    private function config(string $key, $default = null)
    {
        return config('vanilo.order.number.nano_id.' . $key, $default);
    }
}
