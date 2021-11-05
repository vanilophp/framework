<?php

declare(strict_types=1);

/**
 * Contains the BillingSubject class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-12-04
 *
 */

namespace Vanilo\Checkout\Tests\Example;

use Illuminate\Support\Arr;
use Vanilo\Contracts\Address as AddressContract;

class Billpayer implements \Vanilo\Contracts\Billpayer
{
    /** @var Address */
    public $address;

    protected $data;

    public function __construct(array $data = null)
    {
        $this->data = $data ? Arr::except($data, 'address') : [];

        $this->address = new Address($data['address'] ?? []);
    }

    /**
     * @inheritDoc
     */
    public function __set($name, $value)
    {
        if ('billingAddress' == $name) {
            foreach ($value as $key => $value) {
                $this->getBillingAddress()->{$key} = $value;
            }
        } else {
            $this->data[$name] = $value;
        }
    }

    public function isEuRegistered(): bool
    {
        return $this->data['is_eu_registered'] ?? false;
    }

    public function getBillingAddress(): AddressContract
    {
        return $this->address;
    }

    public function getEmail(): ?string
    {
        return $this->data['email'] ?? null;
    }

    public function getPhone(): ?string
    {
        return $this->data['phone'] ?? null;
    }

    public function getName(): string
    {
        return $this->isOrganization() ? $this->getCompanyName() : $this->getFullName();
    }

    public function isOrganization(): bool
    {
        return $this->data['is_organization'] ?? false;
    }

    public function isIndividual(): bool
    {
        return $this->data['is_individual'] ?? false;
    }

    public function getCompanyName(): ?string
    {
        return $this->data['company_name'] ?? null;
    }

    public function getTaxNumber(): ?string
    {
        return $this->data['tax_nr'] ?? null;
    }

    public function getFirstName(): ?string
    {
        return $this->data['first_name'] ?? null;
    }

    public function getLastName(): ?string
    {
        return $this->data['last_name'] ?? null;
    }

    public function getFullName(): string
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }
}
