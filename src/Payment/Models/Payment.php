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
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use Konekt\Enum\Eloquent\CastsEnums;
use Vanilo\Contracts\Payable;
use Vanilo\Payment\Contracts\Payment as PaymentContract;
use Vanilo\Payment\Contracts\PaymentMethod;
use Vanilo\Payment\Contracts\PaymentStatus;
use Vanilo\Support\Generators\NanoIdGenerator;

/**
 * @property int $id
 * @property int $payment_method_id
 * @property int $payable_id
 * @property string $payable_type
 * @property Payable $payable
 * @property PaymentMethod $method
 * @property PaymentStatus $status
 * @property Collection $history
 * @property null|string $status_message
 * @property null|string $hash
 * @property null|string $remote_id
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

    protected $casts = [
        'data' => 'json'
    ];

    protected $attributes = [
        'amount_paid' => 0,
    ];

    public function __construct(array $attributes = [])
    {
        if (!isset($attributes['status'])) {
            $this->setDefaultPaymentStatus();
        }

        if (!isset($attributes['hash'])) {
            $this->generateHash();
        }

        parent::__construct($attributes);
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (null === $model->data) {
                $model->data = [];
            }
        });
    }

    /* An alias of findByHash to comply with the Payment interface */
    public static function findByPaymentId(string $paymentId): ?Payment
    {
        return static::findByHash($paymentId);
    }

    public static function findByHash(string $hash): ?Payment
    {
        return static::where('hash', $hash)->first();
    }

    public static function findByRemoteId(string $remoteId, int $paymentMethodId = null): ?Payment
    {
        if (null === $paymentMethodId) {
            return static::where('remote_id', $remoteId)->first();
        }

        return static::where('remote_id', $remoteId)
            ->where('payment_method_id', $paymentMethodId)
            ->first();
    }

    public function getPaymentId(): string
    {
        return $this->hash;
    }

    public function getAmount(): float
    {
        return (float) $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getAmountPaid(): float
    {
        return (float) $this->amount_paid;
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

    public function getExtraData(): array
    {
        return $this->data ?? [];
    }

    public function history(): HasMany
    {
        return $this->hasMany(PaymentHistoryProxy::modelClass(), 'payment_id', 'id');
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

    private function generateHash()
    {
        $this->setRawAttributes(
            array_merge(
                $this->attributes,
                [
                    'hash' => (new NanoIdGenerator())->generate()
                ]
            ),
            true
        );
    }
}
