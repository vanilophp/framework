<?php
/**
 * Contains the BillPayer model class.
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
use Konekt\Enum\Eloquent\CastsEnums;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Billpayer as VaniloBillPayerContract;
use Vanilo\Order\Contracts\Billpayer as BillPayerContract;

/**
 * This is a temporary class in order to make checkout and order
 * work temporarily as of v0.1. Probably will be moved to the
 * billing module or another module, if it survives at all
 */
class Billpayer extends Model implements BillPayerContract, VaniloBillPayerContract
{
    use CastsEnums;

    protected $guarded = ['id', 'address_id'];

    protected $enums = [
        'type' => '\Konekt\Customer\Models\CustomerTypeProxy@enumClass'
    ];

    public function isEuRegistered()
    {
        return $this->is_eu_registered;
    }

    public function billingAddress()
    {
        return $this->belongsTo(AddressProxy::modelClass());
    }

    public function getBillingAddress(): Address
    {
        return $this->billingAddress;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getName()
    {
        if ($this->isOrganization()) {
            return $this->getCompanyName();
        } else {
            return $this->getFullName();
        }
    }

    public function isOrganization()
    {
        return $this->type->isOrganization();
    }

    public function isIndividual()
    {
        return $this->type->isIndividual();
    }

    public function getCompanyName()
    {
        return $this->company_name;
    }

    public function getTaxNumber()
    {
        return $this->tax_nr;
    }

    public function getFirstName()
    {
        return $this->firstname;
    }

    public function getLastName()
    {
        return $this->lastname;
    }

    public function getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }
}
