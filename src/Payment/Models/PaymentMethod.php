<?php

declare(strict_types=1);

/**
 * Contains the PaymentMethod class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-04-26
 *
 */

namespace Vanilo\Payment\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Nette\Schema\Expect;
use Vanilo\Contracts\Schematized;
use Vanilo\Payment\Contracts\PaymentGateway;
use Vanilo\Payment\Contracts\PaymentMethod as PaymentMethodContract;
use Vanilo\Payment\Gateways\NullGateway;
use Vanilo\Payment\PaymentGateways;
use Vanilo\Support\Dto\SchemaDefinition;
use Vanilo\Support\Traits\ConfigurableModel;

/**
 * @property int $id
 * @property string $name
 * @property null|string $description
 * @property string $gateway
 * @property array $configuration
 * @property bool $is_enabled
 * @property integer $transaction_count
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property null|Carbon $deleted_at
 * @method PaymentMethod create(array $attributes)
 */
class PaymentMethod extends Model implements PaymentMethodContract
{
    use ConfigurableModel;

    protected $guarded = ['id', 'transaction_count', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'configuration' => 'json'
    ];

    public function getTimeout(): int
    {
        if (!is_array($this->configuration)) {
            return PaymentMethodContract::DEFAULT_TIMEOUT;
        }

        return Arr::get($this->configuration, 'timeout', PaymentMethodContract::DEFAULT_TIMEOUT);
    }

    public function getGateway(): PaymentGateway
    {
        if (null === $this->gateway) {
            return new NullGateway();
        }

        return PaymentGateways::make($this->gateway);
    }

    /** @deprecated use the `configuration()` method instead */
    public function getConfiguration(): array
    {
        return $this->configuration() ?? [];
    }

    public function getConfigurationSchema(): ?Schematized
    {
        return new SchemaDefinition(
            Expect::structure(['timeout' => Expect::int(PaymentMethodContract::DEFAULT_TIMEOUT)]),
            ['timeout' => PaymentMethodContract::DEFAULT_TIMEOUT],
        );
    }

    public function isEnabled(): bool
    {
        return (bool) $this->is_enabled;
    }

    public function getName(): string
    {
        return (string) $this->name;
    }

    public function scopeActives(Builder $query)
    {
        return $query->where('is_enabled', true);
    }

    public function scopeInActives(Builder $query)
    {
        return $query->where('is_enabled', false);
    }

    public function getGatewayName(): string
    {
        if (null === $this->gateway) {
            return NullGateway::getName();
        }

        $gwClass = PaymentGateways::getClass($this->gateway);

        return $gwClass::getName();
    }
}
