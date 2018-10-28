<?php
/**
 * Contains the CreateTaxonForm interface.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-10-28
 *
 */

namespace Vanilo\Framework\Contracts\Requests;

use Konekt\Concord\Contracts\BaseRequest;
use Vanilo\Category\Contracts\Taxon;

interface CreateTaxonForm extends BaseRequest
{
    /**
     * Returns the Taxon the new taxon should be the child of
     *
     * @return null|Taxon
     */
    public function getDefaultParent();

    /**
     * Returns the proposed priority value for a new taxon
     * @param Taxon $taxon
     * @return int
     */
    public function getNextPriority(Taxon $taxon): int;
}
