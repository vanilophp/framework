<?php

declare(strict_types=1);

/**
 * Contains the PaymentHistory class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-03-21
 *
 */

namespace Vanilo\Payment\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Konekt\Enum\Eloquent\CastsEnums;
use Vanilo\Payment\Contracts\Payment;
use Vanilo\Payment\Contracts\PaymentHistory as PaymentHistoryContract;
use Vanilo\Payment\Contracts\PaymentResponse;
use Vanilo\Payment\Contracts\PaymentStatus;

/**
 * @property int           payment_id
 * @property Payment       $payment
 * @property PaymentStatus old_status
 * @property PaymentStatus new_status
 * @property ?string       $message
 * @property ?string       $native_status
 * @property ?float        $transaction_amount
 * @property ?string       $transaction_number
 * @property Carbon        $created_at
 * @property Carbon        $updated_at
 * @method static PaymentHistory create(array $attributes)
 */
class PaymentHistory extends Model implements PaymentHistoryContract
{
    use CastsEnums;

    protected $table = 'payment_history';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $enums = [
        'old_status' => 'PaymentStatusProxy@enumClass',
        'new_status' => 'PaymentStatusProxy@enumClass',
    ];

    public static function writePaymentResponseToHistory(
        Payment $payment,
        PaymentResponse $response,
        PaymentStatus $oldStatus = null
    ): PaymentHistoryContract {
        return PaymentHistoryProxy::create([
           'payment_id' => $payment->id,
           'old_status' => $oldStatus ?: $payment->getStatus()->value(),
           'new_status' => $response->getStatus()->value(),
           'message' => $response->getMessage(),
           'transaction_amount' => $response->getAmountPaid(),
           'native_status' => $response->getNativeStatus()->value(),
           'transaction_number' => $response->getTransactionId(),
        ]);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(PaymentProxy::modelClass(), 'id', 'payment_id');
    }
}
