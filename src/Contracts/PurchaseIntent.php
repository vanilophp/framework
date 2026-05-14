<?php

namespace Vanilo\Contracts;

interface PurchaseIntent extends LineItemContainer, Shippable, Billable
{
    public function itemsTotal(): float;

    /** The two-letter ISO 639-1 code */
    public function getLanguage(): ?string;
}
