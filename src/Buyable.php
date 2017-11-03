<?php
/**
 * Contains the Buyable interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-28
 *
 */

namespace Vanilo\Contracts;

interface Buyable
{
    /**
     * Returns the id of the _thing_
     *
     * @return int
     */
    public function getId();

    /**
     * Returns the name of the _thing_
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the price of the item; float is temporary!!
     *
     * @todo Make the decision with Vanilo 0.2 about how to handle prices:
     *       Decimal/Money/PreciseMoney/json with various currencies/...
     *
     * @return float
     */
    public function getPrice();

    /**
     * Return the name to use for saving to the db as type name.
     *
     * It could be either the full class name, or any other string.
     *
     * 1. If it's not the FQCN, you must add the type to `Relation::morphMap`
     *
     * 2. It it's the FQCN, then the full class name gets saved. It's
     *    more simple, but if you'll ever change the classname, it
     *    means you need to migrate the DB to replace the names
     *
     * @see https://laravel.com/docs/5.5/eloquent-relationships#polymorphic-relations -> Custom Polymorphic Types
     *
     * @return string
     */
    public function morphTypeName(): string;
}
