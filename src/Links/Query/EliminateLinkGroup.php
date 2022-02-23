<?php

declare(strict_types=1);

/**
 * Contains the EliminateLinkGroup class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-23
 *
 */

namespace Vanilo\Links\Query;

use Vanilo\Links\Contracts\LinkType;

class EliminateLinkGroup
{
    public function __construct(private LinkType $type)
    {
    }
}
