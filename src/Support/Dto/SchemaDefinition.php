<?php

declare(strict_types=1);

/**
 * Contains the SchemaDefinition class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-02-27
 *
 */

namespace Vanilo\Support\Dto;

use Nette\Schema\Schema;
use Vanilo\Contracts\Schematized;

final class SchemaDefinition implements Schematized
{
    public function __construct(
        private readonly Schema $schema,
        private readonly array $sample,
    ) {
    }

    public static function wrap(Schematized $schematized)
    {
        return new self($schematized->getSchema(), $schematized->getSchemaSample());
    }

    public function getSchema(): Schema
    {
        return $this->schema;
    }

    public function getSchemaSample(?array $mergeWith = null): array
    {
        return $this->sample;
    }
}
