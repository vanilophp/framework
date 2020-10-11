<?php
/**
 * Contains the Taxon interface.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-08-27
 */

namespace Vanilo\Category\Contracts;

interface Taxon
{
    public function setParent(Taxon $taxon);

    public function setTaxonomy(Taxonomy $taxonomy);

    public function isRootLevel(): bool;

    public function removeParent();

    /**
     * Returns the highest priority taxon from the same level
     *
     * @param bool $excludeSelf Whether or not to exclude the taxon itself from the neighbours
     *
     * @return Taxon|null
     */
    public function lastNeighbour(bool $excludeSelf = false): ?Taxon;

    /**
     * Returns the lowest priority taxon from the same level
     *
     * @param bool $excludeSelf Whether or not to exclude the taxon itself from the neighbours
     *
     * @return Taxon|null
     */
    public function firstNeighbour(bool $excludeSelf = false): ?Taxon;
}
