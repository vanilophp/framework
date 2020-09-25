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

final class NanoIdGenerator implements OrderNumberGenerator
{
    private const ALPHABET = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    private $alphabet;

    private $size = 12;

    public function __construct(int $size = null, string $alphabet = null)
    {
        $this->alphabet = $alphabet ?? $this->config('alphabet', self::ALPHABET);
        $this->size = $size ?? $this->config('size', $this->size);
    }

    public function generateNumber(Order $order = null): string
    {
        $len = strlen($this->alphabet);
        $mask = (2 << log($len - 1) / M_LN2) - 1;
        $step = (int) ceil(1.6 * $mask * $this->size / $len);
        $id = '';
        while (true) {
            $bytes = $this->random($step);
            for ($i = 1; $i <= $step; $i++) {
                $byte = $bytes[$i] & $mask;
                if (isset($this->alphabet[$byte])) {
                    $id .= $this->alphabet[$byte];
                    if (strlen($id) === $this->size) {
                        return $id;
                    }
                }
            }
        }
    }

    private function config(string $key, $default = null)
    {
        return config('vanilo.order.number.nano_id.' . $key, $default);
    }

    private function random($size)
    {
        return unpack('C*', \random_bytes($size));
    }
}
