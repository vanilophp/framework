<?php
/**
 * Contains the SyncModelTaxons request interface.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-11-10
 *
 */

namespace Vanilo\Framework\Contracts\Requests;

use Illuminate\Database\Eloquent\Model;
use Konekt\Concord\Contracts\BaseRequest;

interface SyncModelTaxons extends BaseRequest
{
    /**
     * Returns the model taxons need to be synchronized for
     *
     * @return null|Model
     */
    public function getFor();

    public function getTaxonIds(): array;
}
