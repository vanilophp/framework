<?php
/**
 * Contains the Billpayer model class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-12-12
 *
 */

namespace Vanilo\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Konekt\Address\Models\AddressProxy;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Billpayer as VaniloBillpayerContract;
use Vanilo\Order\Contracts\Billpayer as BillpayerContract;

/**
 * This is a temporary class in order to make checkout and order
 * work temporarily as of v0.1. Probably will be moved to the
 * billing module or another module, if it survives at all
 */
class Billpayer extends Model implements BillpayerContract, VaniloBillpayerContract
{
    protected $guarded = ['id', 'address_id'];

    public function isEuRegistered(): bool
    {
        return (bool) $this->is_eu_registered;
    }

    public function address()
    {
        return $this->belongsTo(AddressProxy::modelClass());
    }

    public function getBillingAddress(): Address
    {
        return $this->address;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getName(): string
    {
        if ($this->isOrganization()) {
            return $this->getCompanyName();
        }

        return $this->getFullName();
    }

    public function isOrganization(): bool
    {
        return (bool) $this->is_organization;
    }

    public function isIndividual(): bool
    {
        return !$this->isOrganization();
    }

    public function getCompanyName(): ?string
    {
        return $this->company_name;
    }

    public function getTaxNumber(): ?string
    {
        return $this->tax_nr;
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
        return $this->getFirstName() . ' ' . $this->getLastName();
    }
}
