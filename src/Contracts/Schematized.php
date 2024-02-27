<?php

declare(strict_types=1);

/**
 * Contains the Schematized interface.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-02-27
 *
 */

namespace Vanilo\Contracts;

use Nette\Schema\Schema;

interface Schematized
{
    public function getSchema(): Schema;

    public function getSchemaSample(array $mergeWith = null): array;

}
