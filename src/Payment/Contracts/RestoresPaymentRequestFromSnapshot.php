<?php

declare(strict_types=1);

namespace Vanilo\Payment\Contracts;

use Vanilo\Snapshot\Contracts\Snapshot;

interface RestoresPaymentRequestFromSnapshot
{
    public function restorePaymentRequestFromSnapshot(Snapshot $snapshot): PaymentRequest;
}
