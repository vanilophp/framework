<?php

declare(strict_types=1);

/**
 * Contains the Address class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-09-24
 *
 */

namespace Vanilo\Contracts\Tests\Dummies;

class Address implements \Vanilo\Contracts\Address
{
    /** @var string */
    private $name;

    /** @var string */
    private $countryCode;

    /** @var string */
    private $address;

    /** @var string|null */
    private $provinceCode;

    /** @var string|null */
    private $postalCode;

    /** @var string|null */
    private $city;

    public function __construct(
        string $name = null,
        string $countryCode = null,
        string $address = null,
        ?string $provinceCode = null,
        ?string $postalCode = null,
        ?string $city = null
    ) {
        $this->name = $name;
        $this->countryCode = $countryCode;
        $this->address = $address;
        $this->provinceCode = $provinceCode;
        $this->postalCode = $postalCode;
        $this->city = $city;
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
        return null;
    }
}
