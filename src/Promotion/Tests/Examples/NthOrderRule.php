<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests\Examples;

use Nette\Schema\Schema;
use Vanilo\Promotion\Contracts\PromotionRuleType;

class NthOrderRule implements PromotionRuleType
{
    public static function getName(): string
    {
        return 'Nth Order';
    }

    public function getTitle(array $configuration): string
    {
        return 'Nth Order';
    }

    public function isPassing(object $subject, array $configuration): bool
    {
        // TODO: Implement isPassing() method.
    }

    public function getSchema(): Schema
    {
        // TODO: Implement getSchema() method.
    }

    public function getSchemaSample(array $mergeWith = null): array
    {
        // TODO: Implement getSchemaSample() method.
    }
}
