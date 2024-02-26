<?php

declare(strict_types=1);

/**
 * Contains the TaxCategory interface.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-17
 *
 */

namespace Vanilo\Taxes\Contracts;

interface TaxCategory
{
    public function getName(): string;

    public function getType(): TaxCategoryType;

    public static function findByName(string $name): ?TaxCategory;
}
