<?php

declare(strict_types=1);

/**
 * Contains the DummyAddress class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-02-16
 *
 */

namespace Vanilo\Support\Tests\Dummies;

use Vanilo\Contracts\Address;

class DummyAddress implements Address
{
    public function __construct(
        public ?string $name = null,
        public ?string $countryCode = null,
        public ?string $provinceCode = null,
        public ?string $postalCode = null,
        public ?string $city = null,
        public ?string $address = null,
    ) {
    }

    public function getName(): string
    {
        return $this->name ?? '';
    }

    public function getCountryCode(): string
    {
        return $this->countryCode ?? '';
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
        return $this->address ?? '';
    }

    public function getAddress2(): ?string
    {
        return null;
    }
}
