<?php

declare(strict_types=1);

/**
 * Contains the IssuedPaymentRequest class.
 *
 * @copyright   Copyright (c) 2026 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2026-11-05
 *
 */

namespace Vanilo\Payment\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vanilo\Payment\Contracts\IssuedPaymentRequest as IssuedPaymentRequestContract;
use Vanilo\Payment\Contracts\Payment;

/**
 * @property int      $id
 * @property int      $payment_id
 * @property ?int     $snapshot_id
 * @property Payment  $payment
 * @property Carbon   $created_at
 * @property Carbon   $updated_at
 * @method static IssuedPaymentRequest create(array $attributes)
 */
class IssuedPaymentRequest extends Model implements IssuedPaymentRequestContract
{
    protected $table = 'issued_payment_requests';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function hasSnapshot(): bool
    {
        return null !== $this->snapshot_id;
    }

    public function getPayment(): Payment
    {
        return $this->payment;
    }

    public function getSnapshotId(): ?int
    {
        return $this->snapshot_id;
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(PaymentProxy::modelClass(), 'payment_id');
    }
}
