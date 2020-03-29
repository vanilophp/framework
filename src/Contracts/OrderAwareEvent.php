<?php
/**
 * Contains the OrderAwareEvent interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-30
 *
 */

namespace Vanilo\Order\Contracts;

interface OrderAwareEvent
{
    public function getOrder(): Order;
}
