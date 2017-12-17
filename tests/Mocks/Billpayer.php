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


use Vanilo\Contracts\Address as AddressContract;

class Billpayer implements \Vanilo\Contracts\Billpayer
{
    protected $data;

    /** @var Address */
    public $address;

    public function __construct(array $data = null)
    {
        $this->data = $data ? array_except($data, 'address'): [];

        $this->address = new Address($data['address'] ?? []);
    }

    public function isEuRegistered()
    {
        return $this->data['is_eu_registered'] ?? null;
    }

    public function getBillingAddress(): AddressContract
    {
        return $this->address;
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
}
