<?php

declare(strict_types=1);

/**
 * Contains the HasPrivateLinkTypeBasedConstructor trait.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-23
 *
 */

namespace Vanilo\Links\Query;

use Vanilo\Links\Contracts\LinkType;
use Vanilo\Links\Traits\NormalizesLinkType;

trait HasPrivateLinkTypeBasedConstructor
{
    use NormalizesLinkType;

    protected LinkType $type;

    private function __construct(LinkType|string $type)
    {
        $this->type = $this->normalizeLinkTypeModel($type);
    }
}
