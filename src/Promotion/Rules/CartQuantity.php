<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Rules;

use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\Schema;
use Vanilo\Cart\Contracts\Cart;
use Vanilo\Promotion\Contracts\PromotionRuleType;

class CartQuantity implements PromotionRuleType
{
    private ?array $configuration = null;

    public static function getName(): string
    {
        return __('Cart quantity');
    }

    public static function getID(): string
    {
        return 'cart_quantity';
    }

    public function setConfiguration(array $configuration): self
    {
        if ($this->getSchema()) {
            $configuration = (new Processor())->process($this->getSchema(), $configuration);
        }

        $this->configuration = (array) $configuration;

        return $this;
    }

    public function getConfiguration(): ?array
    {
        $configuration = $this->configuration;

        if ($this->getSchema()) {
            $configuration = (new Processor())->process($this->getSchema(), $configuration);
        }

        return (array) $configuration;
    }

    public function getSchema(): ?Schema
    {
        return Expect::structure(['count' => Expect::int(0)->required()]);
    }

    public function isPassing(object $subject): bool
    {
        if (!$subject instanceof Cart) {
            throw new \InvalidArgumentException('Subject must be an instance of Vanilo\Cart\Contracts\Cart');
        }

        if (!$this->getConfiguration()) {
            return false;
        }

        return $subject->itemCount() <= $this->configuration['count'];
    }
}
