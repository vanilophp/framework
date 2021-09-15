<?php

declare(strict_types=1);

/**
 * Contains the PaymentProxy class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-28
 *
 */

namespace Vanilo\Payment\Models;

use Konekt\Concord\Proxies\ModelProxy;

/**
 * @method static null|Payment findByHash(string $hash)
 * @method static null|Payment findByRemoteId(string $remoteId, int $paymentMethodId = null)
 */
class PaymentProxy extends ModelProxy
{
}
