<?php

declare(strict_types=1);

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

use Illuminate\Database\Eloquent\Casts\Attribute;
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

/**
 * @property-read string $country_id
 * @property-read string|null $province_id
 * @property-read string|null $postalcode
 * @property-read string $city
 * @property-read string $street_address
 * @property-read string|null $address2
 * @property-read string|null $access_code
 */
class Billpayer extends Model implements BillpayerContract, VaniloBillpayerContract
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

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
        if ($this->isOrganization() && $this->getCompanyName()) {
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

    protected function countryId(): Attribute
    {
        return Attribute::get(fn ($value) => $this->getBillingAddress()->getCountryCode());
    }

    protected function provinceId(): Attribute
    {
        return Attribute::get(fn ($value) => $this->getBillingAddress()->province_id);
    }

    protected function postalcode(): Attribute
    {
        return Attribute::get(fn ($value) => $this->getBillingAddress()->getPostalCode());
    }

    protected function city(): Attribute
    {
        return Attribute::get(fn ($value) => $this->getBillingAddress()->getCity());
    }

    /** @todo this would collide with the address() thus the `address` -> `street_address` workaround */
    protected function streetAddress(): Attribute
    {
        return Attribute::get(fn ($value) => $this->getBillingAddress()->getAddress());
    }

    protected function address2(): Attribute
    {
        return Attribute::get(fn ($value) => $this->getBillingAddress()->getAddress2());
    }

    protected function accessCode(): Attribute
    {
        return Attribute::get(fn ($value) => $this->getBillingAddress()->access_code);
    }
}
