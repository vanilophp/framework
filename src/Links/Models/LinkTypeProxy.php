<?php

declare(strict_types=1);

/**
 * Contains the LinkTypeProxy class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-16
 *
 */

namespace Vanilo\Links\Models;

use Konekt\Concord\Proxies\ModelProxy;
use Vanilo\Links\Contracts\LinkType as LinkTypeContract;

/**
 * @method static LinkTypeContract|null findBySlug(string $slug)
 */
class LinkTypeProxy extends ModelProxy
{
}
