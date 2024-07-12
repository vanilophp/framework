<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Actions;

use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\Schema;
use Vanilo\Adjustments\Adjusters\SimpleDiscount;
use Vanilo\Adjustments\Contracts\Adjuster;
use Vanilo\Cart\Contracts\Cart;
use Vanilo\Promotion\Contracts\PromotionActionType;

class CartFixedDiscount implements PromotionActionType
{
    private ?array $configuration = null;

    public static function getName(): string
    {
        return __('Cart fixed discount');
    }

    public static function getID(): string
    {
        return 'cart_fixed_discount';
    }

    public function adjust(object $subject): Adjuster
    {
        if (!$subject instanceof Cart) {
            throw new \InvalidArgumentException('Subject must be an instance of ' . Cart::class);
        }

        return new SimpleDiscount($this->getConfiguration()['discount_amount'] / 100 * $subject->total());
    }

    public function getSchema(): ?Schema
    {
        return Expect::structure(['discount_amount' => Expect::int(0)->required()]);
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
}
