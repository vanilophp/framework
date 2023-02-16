<?php

declare(strict_types=1);

/**
 * Contains the AddressComparisonResult class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-02-16
 *
 */

namespace Vanilo\Support\Utils;

final class AddressComparisonResult
{
    public function __construct(
        public bool $nameDiffers,
        public bool $countryDiffers,
        public bool $provinceDiffers,
        public bool $postalCodeDiffers,
        public bool $cityDiffers,
        public bool $addressDiffers,
    ) {
    }

    /**
     * The addresses are fully identical (all parts of it)
     */
    public function identical(): bool
    {
        return $this->atTheSameAddress() && $this->forTheSamePerson();
    }

    /**
     * The addresses are not fully identical
     */
    public function different(): bool
    {
        return !$this->identical();
    }

    /**
     * The names of the addresses are the same
     */
    public function forTheSamePerson(): bool
    {
        return !$this->nameDiffers;
    }

    /**
     * The names of the addresses are different
     */
    public function forDifferentPeople(): bool
    {
        return $this->nameDiffers;
    }

    /**
     * The countries, the provinces, the cities, the postal codes and the addresses are the same
     */
    public function atTheSameAddress(): bool
    {
        return $this->inTheSameDistrict() && !$this->addressDiffers;
    }

    /**
     * The countries, the provinces, the cities, the postal codes and the addresses are not all the same
     */
    public function atDifferentAddresses(): bool
    {
        return !$this->atTheSameAddress();
    }

    /**
     * The countries, the provinces, the cities and the postal codes are the same
     */
    public function inTheSameDistrict(): bool
    {
        return $this->inTheSameCity() && !$this->postalCodeDiffers;
    }

    /**
     * The countries, the provinces, the cities and the postal codes are not all the same
     */
    public function inDifferentDistricts(): bool
    {
        return !$this->inTheSameDistrict();
    }

    /**
     * The countries, the provinces and the cities are the same
     */
    public function inTheSameCity(): bool
    {
        return $this->inTheSameProvince() && !$this->cityDiffers;
    }

    /**
     * The countries, the provinces and the cities are not all the same
     */
    public function inDifferentCities(): bool
    {
        return !$this->inTheSameCity();
    }

    /**
     * The countries and the provinces are the same
     */
    public function inTheSameProvince(): bool
    {
        return $this->inTheSameCountry() && !$this->provinceDiffers;
    }

    /**
     * The countries and the provinces are not all the same
     */
    public function inDifferentProvinces(): bool
    {
        return !$this->inTheSameProvince();
    }

    /**
     * The countries of the two addresses are the same
     */
    public function inTheSameCountry(): bool
    {
        return !$this->countryDiffers;
    }

    /**
     * The countries of the two addresses differ
     */
    public function inDifferentCountries(): bool
    {
        return $this->countryDiffers;
    }
}
