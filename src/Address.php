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
    /**
     * The name on the address
     *
     * @return string
     */
    public function getName();

    /**
     * The country's ISO 3166-1 alpha-2 code
     *
     * @return string
     */
    public function getCountryCode();

    /**
     * Returns the province (state, county, region, etc) code in national notation
     *
     * @return string|null
     */
    public function getProvinceCode();

    /**
     * Returns the postal code of the address
     * 
     * @return string|null
     */
    public function getPostalCode();

    /**
     * Returns the name of the city
     * 
     * @return string
     */
    public function getCity();

    /**
     * The address part (Street, number, building, etc)
     *
     * @return string
     */
    public function getAddress();

}