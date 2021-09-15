<?php

declare(strict_types=1);

/**
 * Contains the Customer class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-28
 *
 */

namespace Vanilo\Payment\Tests\Examples;

use Vanilo\Contracts\Address as AddressContract;
use Vanilo\Contracts\Billpayer;

class Customer implements Billpayer
{
    private $email;

    private $firstname;

    private $lastname;

    public function __construct(
        string $email = 'someone@example.org',
        string $firstname = 'Giovanni',
        string $lastname = 'Gatto'
    ) {
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }

    public function isEuRegistered(): bool
    {
        return false;
    }

    public function getBillingAddress(): AddressContract
    {
        return new Address($this->getFullName());
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return null;
    }

    public function getName(): string
    {
        return $this->getFullName();
    }

    public function isOrganization(): bool
    {
        return false;
    }

    public function isIndividual(): bool
    {
        return true;
    }

    public function getCompanyName(): ?string
    {
        return null;
    }

    public function getTaxNumber(): ?string
    {
        return null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstname;
    }

    public function getLastName(): ?string
    {
        return $this->lastname;
    }

    public function getFullName(): string
    {
        return $this->firstname . ' ' . $this->lastname;
    }
}
