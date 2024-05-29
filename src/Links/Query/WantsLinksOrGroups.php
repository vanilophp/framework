<?php

declare(strict_types=1);

/**
 * Contains the WantsLinksOrGroups trait.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-23
 *
 */

namespace Vanilo\Links\Query;

trait WantsLinksOrGroups
{
    private string $wants = 'links';

    public function links(): self
    {
        $this->wants = 'links';

        return $this;
    }

    public function groups(): self
    {
        $this->wants = 'groups';

        return $this;
    }

    public function linkItems(): self
    {
        $this->wants = 'linkItems';

        return $this;
    }
}
