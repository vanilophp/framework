<?php
/**
 * Contains the Address interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-26
 *
 */

namespace Vanilo\Contracts;

interface Address
{
    public function getName(): string;

    /**
     * The country's ISO 3166-1 alpha-2 code
     */
    public function getCountryCode(): string;

    /**
     * Returns the province (state, county, region, etc) code in national notation
     */
    public function getProvinceCode(): ?string;

    public function getPostalCode(): ?string;

    public function getCity(): string;

    /**
     * The address part (Street, number, building, etc)
     */
    public function getAddress(): string;

    public function isOrganization(): bool;

    public function getOrganizationName(): ?string;

    public function getEmail(): ?string;

    public function getPhone(): ?string;
}
