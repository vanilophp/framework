<?php

declare(strict_types=1);

/**
 * Contains the Merchant interface.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-02-10
 *
 */

namespace Vanilo\Contracts;

interface Merchant extends Contactable
{
    public function getLegalName(): string;

    public function getBrandAliasName(): ?string;

    public function getTaxNumber(): ?string;

    public function getRegistrationNumber(): ?string;

    public function isEuRegistered(): bool;

    public function getAddress(): Address;
}
