<?php

declare(strict_types=1);

/**
 * Contains the Merchant class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-03-11
 *
 */

namespace Vanilo\Support\Dto;

use Konekt\Address\Utils\EuropeanUnion;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Merchant as MerchantContract;

class Merchant implements MerchantContract
{
    public function __construct(
        protected string $name,
        protected Address $address,
        protected ?string $taxNumber,
        protected ?string $registrationNumber,
        protected ?string $email = null,
        protected ?string $phone = null,
        protected ?string $brandAlias = null,
    ) {
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getLegalName(): string
    {
        return $this->name;
    }

    public function getBrandAliasName(): ?string
    {
        return $this->brandAlias;
    }

    public function getTaxNumber(): ?string
    {
        return $this->taxNumber;
    }

    public function getRegistrationNumber(): ?string
    {
        return $this->registrationNumber;
    }

    public function isEuRegistered(): bool
    {
        if (null === $this->taxNumber || strlen($this->taxNumber) < 4) {
            return false;
        }

        if (class_exists(EuropeanUnion::class)) {
            return (bool) EuropeanUnion::validateVatNumberFormat($this->taxNumber);
        }

        // Poor man's guess, but might work in the vast majority of cases
        $vatPrefix = match ($this->address->getCountryCode()) {
            'UK' => 'GB',
            'GR' => 'EL',
            default => $this->address->getCountryCode(),
        };

        return str_starts_with(mb_strtoupper($this->taxNumber), mb_strtoupper($vatPrefix));
    }

    public function getAddress(): Address
    {
        return $this->address;
    }
}
