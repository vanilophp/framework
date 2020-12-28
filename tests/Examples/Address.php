<?php

declare(strict_types=1);

/**
 * Contains the Address class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-28
 *
 */

namespace Vanilo\Payment\Tests\Examples;

use Vanilo\Contracts\Address as AddressContract;

class Address implements AddressContract
{
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCountryCode(): string
    {
        return 'NL';
    }

    public function getProvinceCode(): ?string
    {
        return 'ZH';
    }

    public function getPostalCode(): ?string
    {
        return '2282 NP';
    }

    public function getCity(): ?string
    {
        return 'Rijswijk';
    }

    public function getAddress(): string
    {
        return 'Tulpstraat 109';
    }
}
