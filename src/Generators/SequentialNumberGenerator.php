<?php
/**
 * Contains the SequentialNumberGenerator class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-30
 *
 */


namespace Vanilo\Order\Generators;


use Vanilo\Order\Contracts\Order;
use Vanilo\Order\Contracts\OrderNumberGenerator;
use Vanilo\Order\Models\OrderProxy;

class SequentialNumberGenerator implements OrderNumberGenerator
{
    /** @var int */
    protected $startSequenceFrom;

    /** @var string */
    protected $prefix;

    /** @var int */
    protected $padLength;

    /** @var string */
    protected $padString;

    public function __construct()
    {
        $this->startSequenceFrom = $this->config('start_sequence_from', 1);
        $this->prefix            = $this->config('prefix', '');
        $this->padLength         = $this->config('pad_length', 1);
        $this->padString         = $this->config('pad_string', '0');
    }

    /**
     * @inheritDoc
     */
    public function generateNumber(Order $order = null)
    {
        $lastOrder = OrderProxy::orderBy('id', 'desc')->limit(1)->first();

        $last = $lastOrder ? $lastOrder->id : 0;
        $next = $this->startSequenceFrom + $last;

        return sprintf('%s%s', $this->prefix,
            str_pad($next, $this->padLength, $this->padString, STR_PAD_LEFT)
        );
    }

    /**
     * Returns a configuration value for this particular service
     *
     * @param string    $key
     * @param null      $default
     *
     * @return mixed
     */
    private function config($key, $default = null)
    {
        return config('vanilo.order.number.sequential_number.' . $key, $default);
    }
}
