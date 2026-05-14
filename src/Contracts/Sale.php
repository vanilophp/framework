<?php

declare(strict_types=1);

namespace Vanilo\Contracts;

/**
 * A Sale represents a completed commercial exchange
 * Typically orders, but it can be a subscription
 * a POS transaction, subscription renewal, etc
 */
interface Sale extends PurchaseIntent, Document
{
//    public function getSoldAt();
//
//    public function getMerchant(): ?Merchant;
}
