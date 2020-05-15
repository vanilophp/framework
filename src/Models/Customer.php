<?php
/**
 * Contains the Customer class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-26
 *
 */

namespace Vanilo\Framework\Models;

use Konekt\Customer\Models\Customer as BaseCustomer;
use Vanilo\Contracts\Customer as CustomerContract;

class Customer extends BaseCustomer implements CustomerContract
{
    public function getName(): string
    {
        // Added for interface compatibility
        return parent::getName();
    }

    /**
     * @inheritDoc
     */
    public function getFirstName(): ?string
    {
        return $this->firstname;
    }

    /**
     * @inheritDoc
     */
    public function getLastName(): ?string
    {
        return $this->lastname;
    }

    /**
     * @inheritDoc
     */
    public function getCompanyName(): ?string
    {
        return $this->company_name;
    }

    /**
     * @inheritDoc
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @inheritDoc
     */
    public function getTaxNumber(): ?string
    {
        return $this->tax_nr;
    }

    public function isOrganization(): bool
    {
        return $this->type->isOrganization();
    }

    public function isIndividual(): bool
    {
        return $this->type->isIndividual();
    }

    public function getFullName(): string
    {
        return $this->getFirstName() . ' ' . $this->getLastName(); // This is temporary. My ass.
    }
}
