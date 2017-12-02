<?php
/**
 * Contains the TimeHashGenerator class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-30
 *
 */


namespace Vanilo\Order\Generators;


use Carbon\Carbon;
use Vanilo\Order\Contracts\Order;
use Vanilo\Order\Contracts\OrderNumberGenerator;

/**
 * Generates a time + random based hash.
 *
 * It's good for most of the use cases, but it's uniqueness is not
 * solid in cases when you have to generate >100 numbers per second
 *
 * Setting high_variance to true significantly decreases the chance
 * of possible duplicates, but they are theoretically possible.
 *
 */
class TimeHashGenerator implements OrderNumberGenerator
{
    /** @var bool */
    protected $highVariance;

    public function __construct()
    {
        $this->highVariance = $this->config('high_variance', false);
    }

    /**
     * Set the high variance mode.
     *
     * This can prevent from duplicate order numbers when there's a need to
     * generate hundreds of numbers in a second.
     *
     * When high variance mode is enabled, a longer number gets generated
     * and there's an extra 0.01s delay to overcome issues.
     *
     * @param boolean $value
     */
    public function setHighVariance($value)
    {
        $this->highVariance = (bool)$value;
    }

    /**
     * Returns an order number for an order with a time + random based hash
     *
     * ┌──────────────────────────────────────────────────────────────────────────────────────────────┐
     * │ Format is:                                                                                   │
     * │  - 3 digits: current year as 2 digits(eg 2016 -> 16)                                         │
     * │              + day of the year (eg. 365)                                                     │
     * │              => 16365 converted to 36 scale => cml                                           │
     * │  - dash                                                                                      │
     * │  - 4 digits: second of the day in 36 scale, 0 padded (eg. 82300 -> 1ri4)                     │
     * │  - dash                                                                                      │
     * │  - 2 digits: microtime digits 4-6 in 36scale, (eg. 271 -> 7j, 240 -> 6o)                     │
     * │  - 1 digits: random letter 0-z (0-35)                                                        │
     * │  - 1 digit:  microtime first digit (slowest changing part of microtime)                      │
     * │  If *high_variance* is enabled:                                                              │
     * │  - dash                                                                                      │
     * │  - 2 digits: microtime digits 1-3 in 36scale, (eg. 171 -> 4r)                                │
     * │  - 2 digits: random 2 chars 00-zz                                                            │
     * │                                                                                              │
     * │ N O T E S                                                                                    │
     * │   - The first part turns into 4 digits long after 2047 jan 1st                               │
     * │   - The first part will be 3 digits again after 2100 jan 1st                                 │
     * │                                                                                              │
     * │ Example 1                                                                                    │
     * │ =========                                                                                    │
     * │ ┌───────────────┐                                                                            │
     * │ │ cif-1hdt-7v65 │                                                                            │
     * │ └───────────────┘                                                                            │
     * │  1. Ordering on 2016 aug 3 at 19:11:18 => cif-1hdt   -  7v 6 5                               │
     * │                                          ▲   ▲         ▲  ▲ ▲                                │
     * │                                          │   │         │  │ └──── microtime slow first char  │
     * │                                2016 aug 3┘   └69185sec │  └6 <- random nr                    │
     * │                                                        │                                     │
     * │                                    283, microtime rapid┘                                     │
     * │                                                                                              │
     * └──────────────────────────────────────────────────────────────────────────────────────────────┘
     *
     * @param Order $order
     *
     * @return string
     */
    public function generateNumber(Order $order = null)
    {
        $date = Carbon::now();

        $number =  sprintf('%s-%s-%s%s%s',
            $this->getYearAndDayHash($date),
            str_pad(base_convert($date->secondsSinceMidnight(), 10, 36), 4, '0', STR_PAD_LEFT),
            str_pad(base_convert($this->getRapidMicroNumber(), 10, 36), 2, '0', STR_PAD_LEFT),
            base_convert(mt_rand(0, 35), 10, 36), //always one char
            ((string)$this->getSlowMicroNumber())[0]
        );

        if ($this->highVariance) {
            $number .= '-'
                . str_pad(base_convert($this->getSlowMicroNumber(), 10, 36), 2, '0', STR_PAD_LEFT)
                . str_pad(base_convert(mt_rand(0, 1295), 10, 36), 2, '0', STR_PAD_LEFT);
        }

        return $number;
    }

    /**
     * @param Carbon $date
     *
     * @return string
     */
    protected function getYearAndDayHash(Carbon $date)
    {
        $number = (int)$date->format('y');
        $number *= 1000;
        $number += $date->dayOfYear;

        return str_pad(base_convert($number, 10, 36), 3, '0', STR_PAD_LEFT);
    }


    /**
     * Returns the rapidly changing part of microtime, and makes sure it is >= 36 & < 1296
     *
     * @return int
     */
    protected function getRapidMicroNumber()
    {
        $n = (int)(fmod(((float)microtime())*1000, 1) * 1000) + 36;

        return $n;
    }

    /**
     * Returns the slower changing part of microtime (100 < $result <= 999)
     *
     * @return int
     */
    protected function getSlowMicroNumber()
    {
        $n = (int)(fmod((float)microtime(), 1) * 1000);

        return $n;
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
        return config('vanilo.order.number.time_hash.' . $key, $default);
    }
}
