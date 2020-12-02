<?php
/**
 * Contains the UpdateOrder interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-12-17
 *
 */

namespace Vanilo\Framework\Contracts\Requests;

use Konekt\Concord\Contracts\BaseRequest;
use Vanilo\Order\Contracts\Order;

interface UpdateOrder extends BaseRequest
{
    public function wantsToChangeOrderStatus(Order $order): bool;

    public function getStatus(): string;
}
