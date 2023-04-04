<?php

declare(strict_types=1);

/**
 * Contains the Address class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-04-04
 *
 */

namespace Vanilo\Taxes\Tests\Dummies;

class Address extends \Konekt\Address\Models\Address implements \Vanilo\Contracts\Address
{
    public function getName(): string
    {
        return $this->name;
    }

    public function getCountryCode(): string
    {
        return $this->country_id;
    }

    public function getProvinceCode(): ?string
    {
        return $this->province?->code;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalcode;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getAddress(): string
    {
        return $this->address;
    }
}

