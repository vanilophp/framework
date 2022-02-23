<?php

declare(strict_types=1);

/**
 * Contains the Eliminate class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-23
 *
 */

namespace Vanilo\Links\Query;

use Vanilo\Links\Contracts\LinkType;

final class Eliminate
{
    use HasPrivateLinkTypeBasedConstructor;

    public static function the(LinkType|string $type): self
    {
        return new self($type);
    }

    public function link(): EliminateLinks
    {
        return new EliminateLinks($this->type);
    }

    public function group(): EliminateLinkGroup
    {
        return new EliminateLinkGroup($this->type);
    }
}
