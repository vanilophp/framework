<?php
/**
 * Contains the CreateMedia request interface.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-11-15
 *
 */

namespace Vanilo\Framework\Contracts\Requests;

use Illuminate\Database\Eloquent\Model;
use Konekt\Concord\Contracts\BaseRequest;

interface CreateMedia extends BaseRequest
{
    /**
     * Returns the model media need(s) to be added for
     *
     * @return null|Model
     */
    public function getFor();
}
