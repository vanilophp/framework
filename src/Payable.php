<?php
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

    /** The human readable representation, eg.: "Order no. ABC-123" */
    public function getTitle(): string;
}
