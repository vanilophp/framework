<?php

declare(strict_types=1);

/**
 * Contains the Payment class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-28
 *
 */

namespace Vanilo\Payment\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Konekt\Enum\Eloquent\CastsEnums;
use Vanilo\Contracts\Payable;
use Vanilo\Payment\Contracts\Payment as PaymentContract;
use Vanilo\Payment\Contracts\PaymentMethod;
use Vanilo\Payment\Contracts\PaymentStatus;

/**
 * @property int $id
 * @property int $payment_method_id
 * @property int $payable_id
 * @property string $payable_type
 * @property Payable $payable
 * @property PaymentMethod $method
 * @property PaymentStatus $status
 * @property null|string $hash
 * @property array $data
 * @property float $amount
 * @property string $currency
 * @property float $amount_paid
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @method static Payment create(array $attributes)
 */
class Payment extends Model implements PaymentContract
{
    use CastsEnums;

    protected $table = 'payments';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $enums = [
        'status' => 'PaymentStatusProxy@enumClass',
    ];

    protected $attributes = [
        'amount_paid' => 0,
    ];

    public function __construct(array $attributes = [])
    {
        // Set default status in case there was none given
        if (!isset($attributes['status'])) {
            $this->setDefaultPaymentStatus();
        }

        parent::__construct($attributes);
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getAmountPaid(): float
    {
        return $this->amount_paid;
    }

    public function getStatus(): PaymentStatus
    {
        return $this->status;
    }

    public function getMethod(): PaymentMethod
    {
        return $this->method;
    }

    public function getPayable(): Payable
    {
        return $this->payable;
    }

    public function method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethodProxy::modelClass(), 'payment_method_id');
    }

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    private function setDefaultPaymentStatus()
    {
        $this->setRawAttributes(
            array_merge(
                $this->attributes,
                [
                    'status' => PaymentStatusProxy::defaultValue()
                ]
            ),
            true
        );
    }
}
