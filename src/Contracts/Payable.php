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

interface Payable extends Billable, Document
{
    public function getPayableId(): string;

    public function getPayableType(): string;

    // Add getPaymentMethodId method

    public function getAmount(): float; // @todo v6.0 replace with settleable + total()

    public function getPayableRemoteId(): ?string;

    public function setPayableRemoteId(string $remoteId): void;

    public static function findByPayableRemoteId(string $remoteId): ?Payable;

    public function hasItems(): bool; // @todo v6.0 check: it's redundant from LineItemContainer

    public function getItems(): Traversable;
}
