<?php

declare(strict_types=1);

/**
 * Contains the NormalizesLinkType trait.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-19
 *
 */

namespace Vanilo\Links\Traits;

use Vanilo\Links\Contracts\LinkType;
use Vanilo\Links\Models\LinkTypeProxy;

trait NormalizesLinkType
{
    private array $linkTypeCache = [];

    private function normalizeLinkTypeModel(LinkType|string $type): LinkType
    {
        $slug = is_string($type) ? $type : $type->slug;

        if (!isset($this->linkTypeCache[$slug])) {
            $this->linkTypeCache[$slug] = is_string($type) ? LinkTypeProxy::findBySlug($type) : $type;
        }

        return $this->linkTypeCache[$slug];
    }
}
