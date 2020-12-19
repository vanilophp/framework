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

use Carbon\Carbon;

interface Buyable extends HasImages
{
    public function getId();

    public function getName(): string;

    public function getPrice(): float;

    public function addSale(Carbon $date, $units = 1): void;

    public function removeSale($units = 1): void;

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
