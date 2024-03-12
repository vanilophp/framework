<?php

declare(strict_types=1);

/**
 * Contains the Address class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-03-12
 *
 */

namespace Vanilo\Support\Dto;

use Vanilo\Contracts\Address as AddressContract;

class Address implements AddressContract
{
    public function __construct(
        protected string $name,
        protected string $countryCode,
        protected string $address,
        protected ?string $city,
        protected ?string $postalCode = null,
        protected ?string $address2 = null,
        protected ?string $provinceCode = null,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function getProvinceCode(): ?string
    {
        return $this->provinceCode;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }
}
