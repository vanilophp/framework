<?php

declare(strict_types=1);

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
    /**
     * Returns the name of the person and/or organization belonging to the address
     */
    public function getName(): string;

    /**
     * The country's ISO 3166-1 alpha-2 code
     */
    public function getCountryCode(): string;

    /**
     * Returns the province (state, county, region, etc) code in national notation
     */
    public function getProvinceCode(): ?string;

    /**
     * The postal code, or zip code if applicable
     */
    public function getPostalCode(): ?string;

    /**
     * The city (town, village) or other locality if applicable
     */
    public function getCity(): ?string;

    /**
     * The address part (Street, number, building, PO box etc)
     */
    public function getAddress(): string;
}
