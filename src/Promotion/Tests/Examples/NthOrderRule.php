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

    public static function getID(): string
    {
        // TODO: Implement getID() method.
    }

    public function isPassing(object $subject): bool
    {
        // TODO: Implement isPassing() method.
    }

    public function getSchema(): ?Schema
    {
        // TODO: Implement getSchema() method.
    }

    public function setConfiguration(array $configuration): PromotionRuleType
    {
        // TODO: Implement setConfiguration() method.
    }

    public function getConfiguration(): ?array
    {
        // TODO: Implement getConfiguration() method.
    }
}
