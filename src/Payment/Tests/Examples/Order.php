<?php

declare(strict_types=1);

/**
 * Contains the Order class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-28
 *
 */

namespace Vanilo\Payment\Tests\Examples;

use Illuminate\Database\Eloquent\Model;
use Vanilo\Contracts\Billpayer;
use Vanilo\Contracts\Payable;

class Order extends Model implements Payable
{
    protected $guarded = ['created_at', 'updated_at'];

    public function getPayableId(): string
    {
        return (string) $this->id;
    }

    public function getPayableType(): string
    {
        return self::class;
    }

    public function getTitle(): string
    {
        return sprintf('Order #%d | %f %s', $this->id, $this->total, $this->getCurrency());
    }

    public function getAmount(): float
    {
        return $this->total;
    }

    public function getCurrency(): string
    {
        return 'EUR';
    }

    public function getBillpayer(): ?Billpayer
    {
        return new Customer();
    }

    public function getNumber(): string
    {
        return (string) $this->id;
    }

    public function getPayableRemoteId(): ?string
    {
        return $this->remote_id;
    }

    public function setPayableRemoteId(string $remoteId): void
    {
        $this->remote_id = $remoteId;
    }

    public static function findByPayableRemoteId(string $remoteId): ?Payable
    {
        return self::query()->where('remote_id', $remoteId)->first();
    }
}
