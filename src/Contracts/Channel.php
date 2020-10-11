<?php
/**
 * Contains the Channel interface.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-07-30
 *
 */

namespace Vanilo\Channel\Contracts;

interface Channel
{
    public function getName(): string;

    public function getSlug(): ?string;
}
