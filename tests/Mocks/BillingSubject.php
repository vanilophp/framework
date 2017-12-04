<?php
/**
 * Contains the BillingSubject class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-12-04
 *
 */


namespace Vanilo\Checkout\Tests\Mocks;


use Vanilo\Contracts\Address;

class BillingSubject implements \Vanilo\Contracts\BillingSubject
{
    protected $data;

    public function __construct(array $data = null)
    {
        $this->data = $data ?: [];
    }

    public function isEuRegistered()
    {
        return $this->data['is_eu_registered'] ?? null;
    }

    public function getAddress(): Address
    {
        // TODO: Implement getAddress() method.
    }

    public function getEmail()
    {
        return $this->data['email'] ?? null;
    }

    public function getPhone()
    {
        return $this->data['phone'] ?? null;
    }

    public function getName()
    {
        return $this->isOrganization() ? $this->getCompanyName() : $this->getFullName();
    }

    public function isOrganization()
    {
        return $this->data['is_organization'] ?? false;
    }

    public function isIndividual()
    {
        return $this->data['is_individual'] ?? false;
    }

    public function getCompanyName()
    {
        return $this->data['company_name'] ?? null;
    }

    public function getTaxNumber()
    {
        return $this->data['tax_nr'] ?? null;
    }

    public function getFirstName()
    {
        return $this->data['first_name'] ?? null;
    }

    public function getLastName()
    {
        return $this->data['last_name'] ?? null;
    }

    public function getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }
}
