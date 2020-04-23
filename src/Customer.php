<?php
/**
 * Contains the Customer interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-26
 *
 */

namespace Vanilo\Contracts;

interface Customer extends Organization, Person
{
    /**
     * Returns the name of the customer (either company or person's name)
     */
    public function getName(): string;

    /**
     * Returns whether the client is an organization (company, GO, NGO, foundation, etc)
     */
    public function isOrganization(): bool;

    /**
     * Returns whether the client is a natural person
     */
    public function isIndividual(): bool;
}
