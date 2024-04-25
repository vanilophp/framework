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

use Traversable;

interface Payable
{
    public function getPayableId(): string;

    public function getPayableType(): string;

    public function getAmount(): float;

    public function getCurrency(): string;

    public function getBillpayer(): ?Billpayer;

    public function getNumber(): string;

    public function getPayableRemoteId(): ?string;

    public function setPayableRemoteId(string $remoteId): void;

    public static function findByPayableRemoteId(string $remoteId): ?Payable;

    public function hasItems(): bool;

    public function getItems(): Traversable;

    /** The human readable representation, eg.: "Order no. ABC-123" */
    public function getTitle(): string;
}
