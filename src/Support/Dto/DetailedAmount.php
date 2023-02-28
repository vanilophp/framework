<?php

declare(strict_types=1);

/**
 * Contains the DetailedAmount class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-02-28
 *
 */

namespace Vanilo\Support\Dto;

use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\Schema;
use Nette\Schema\ValidationException;
use Vanilo\Contracts\DetailedAmount as DetailedAmountContract;

final class DetailedAmount implements DetailedAmountContract
{
    private array $details = [];

    private static Schema $schema;

    private static Processor $validator;

    /**
     * @param float $value
     * @param array{0: array{title: string, amount:float}} $details
     */
    public function __construct(
        private float $value,
        array $details = [],
    ) {
        self::$validator = new Processor();
        self::$schema = Expect::listOf(
            Expect::structure([
                'title' => Expect::string()->required(),
                'amount' => Expect::type('float|int')->required(),
            ])->castTo('array'),
        )->castTo('array');

        if (!empty($details)) {
            $this->details = self::validate($details);
        }
    }

    /** @param array{0: array{title: string, amount:float}} $details */
    public static function fromArray(array $details): self
    {
        $instance = new self(0);
        foreach (self::validate($details) as $detail) {
            $instance->addDetail($detail['title'], $detail['amount'], recalculate: true);
        }

        return $instance;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getDetails(): array
    {
        return $this->details;
    }

    public function addDetail(string $title, float $value, bool $recalculate = true): self
    {
        $this->details[] = [
            'title' => $title,
            'amount' => $value,
        ];

        if ($recalculate) {
            $this->recalculate();
        }

        return $this;
    }

    /** @throws ValidationException */
    private static function validate(array $details): array
    {
        return static::$validator->process(self::$schema, $details);
    }

    private function recalculate(): void
    {
        $this->value = 0;
        foreach ($this->details as $detail) {
            $this->value += $detail['amount'];
        }
    }
}
