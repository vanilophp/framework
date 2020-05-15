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
    public function getId(): string;

    public function getPayableType(): string;

    public function getAmount(): float;

    public function getCurrency(): string;

    public function getBillpayer(): Billpayer;

    public function needsShipping(): bool;

    public function getShippable(): ?Shippable;
}
