<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Contracts;

use Nette\Schema\Schema;
use Vanilo\Adjustments\Contracts\Adjuster;

interface PromotionActionType
{
    public static function getName(): string;

    public static function getID(): string;

    public function adjust(object $subject): Adjuster;

    public function getSchema(): ?Schema;

    public function setConfiguration(array $configuration): self;

    public function getConfiguration(): ?array;
}
