<?php
/**
 * Contains the Taxonomy interface.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-08-25
 */

namespace Vanilo\Category\Contracts;

use Illuminate\Support\Collection;

interface Taxonomy
{
    /**
     * Returns a taxonomy based on its name
     *
     * @param string $name
     *
     * @return Taxonomy|null
     */
    public static function findOneByName(string $name): ?Taxonomy;

    /**
     * Returns the root level taxons for the taxonomy
     *
     * @return Collection
     */
    public function rootLevelTaxons(): Collection;
}
