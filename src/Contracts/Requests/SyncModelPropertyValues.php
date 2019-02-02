<?php
/**
 * Contains the SyncModelPropertyValues interface.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-02-02
 *
 */

namespace Vanilo\Framework\Contracts\Requests;

use Illuminate\Database\Eloquent\Model;
use Konekt\Concord\Contracts\BaseRequest;

interface SyncModelPropertyValues extends BaseRequest
{
    /**
     * Returns the model for which the property values have to be updated
     *
     * @return null|Model
     */
    public function getFor();

    public function getPropertyValueIds(): array;
}
