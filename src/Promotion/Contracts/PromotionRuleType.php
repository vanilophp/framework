<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Contracts;

use Nette\Schema\Schema;

interface PromotionRuleType
{
    public static function getName(): string;

    public static function getID(): string;

    public function isPassing(object $subject): bool;

    public function getSchema(): ?Schema;

    public function setConfiguration(array $configuration): self;

    public function getConfiguration(): ?array;
}
