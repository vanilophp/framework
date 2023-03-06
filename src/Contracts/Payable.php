<?php

declare(strict_types=1);

/**
 * Contains the Payable interface.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-12-17
 *
 */

namespace Vanilo\Contracts;

interface Payable
{
    public function getPayableId(): string;

    public function getPayableType(): string;

    public function getAmount(): float;

    public function getCurrency(): string;

    public function getBillpayer(): ?Billpayer;

    /**
     * @todo add in v4 so that we don't mess with title/id and make it straightforward
     * public function getNumber(): string;
     *
     * @todo add in v4
     * public function getPayableRemoteId(): ?string;
     * public function setPayableRemoteId(string $remoteId): void
     */

    /** The human readable representation, eg.: "Order no. ABC-123" */
    public function getTitle(): string;
}
