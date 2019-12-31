<?php
/**
 * Contains the Decimal class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-12-31
 *
 */

namespace Vanilo\Support;

use Decimal\Decimal as PhpDecimal;
use Vanilo\Contracts\Decimal as DecimalContract;

class Decimal implements DecimalContract
{
    /** @var \Decimal\Decimal */
    private $decimal;

    public function __construct($value, int $precision = DecimalContract::DEFAULT_PRECISION)
    {
        $value = self::ensureCompatibleValue($value);
        $this->decimal = ($value instanceof PhpDecimal) ? $value : new PhpDecimal($value, $precision);
    }

    public static function create($value, int $precision = DecimalContract::DEFAULT_PRECISION): DecimalContract
    {
        return new self($value, $precision);
    }

    public function precision(): int
    {
        return $this->decimal->precision();
    }

    public function isNaN(): bool
    {
        return $this->decimal->isNaN();
    }

    public function isInf(): bool
    {
        return $this->decimal->isInf();
    }

    public function isInteger(): bool
    {
        return $this->decimal->isInteger();
    }

    public function isZero(): bool
    {
        return $this->decimal->isZero();
    }

    public function isPositive(): bool
    {
        return $this->decimal->isPositive();
    }

    public function isNegative(): bool
    {
        return $this->decimal->isNegative();
    }

    public function isEven(): bool
    {
        return $this->decimal->isEven();
    }

    public function isOdd(): bool
    {
        return $this->decimal->isOdd();
    }

    public function abs(): DecimalContract
    {
        return self::create($this->decimal->abs());
    }

    public function negate(): DecimalContract
    {
        return self::create($this->decimal->negate());
    }

    public function floor(): DecimalContract
    {
        return self::create($this->decimal->floor());
    }

    public function ceil(): DecimalContract
    {
        return self::create($this->decimal->ceil());
    }

    public function truncate(): DecimalContract
    {
        return self::create($this->decimal->truncate());
    }

    public function round(int $places = 0, int $mode = PHP_ROUND_HALF_EVEN): DecimalContract
    {
        return self::create($this->decimal->round($places, $mode));
    }

    public function trim(): DecimalContract
    {
        return self::create($this->decimal->trim());
    }

    public function toFixed(
        int $places = 0,
        bool $commas = false,
        int $mode = PHP_ROUND_HALF_EVEN
    ): string {
        return $this->decimal->toFixed($places, $commas, $mode);
    }

    public function equals($other): bool
    {
        return $this->decimal->equals(
            self::ensureCompatibleValue($other)
        );
    }

    public function compareTo($other): int
    {
        return $this->decimal->compareTo(
            self::ensureCompatibleValue($other)
        );
    }

    public function toInt(): int
    {
        return $this->decimal->toInt();
    }

    public function toFloat(): float
    {
        return $this->decimal->toFloat();
    }

    public function toString(): string
    {
        return $this->decimal->toString();
    }

    public function add($value): DecimalContract
    {
        return self::create($this->decimal->add(
            self::ensureCompatibleValue($value)
        ));
    }

    public function sub($value): DecimalContract
    {
        return self::create($this->decimal->sub(
            self::ensureCompatibleValue($value)
        ));
    }

    public function mul($value): DecimalContract
    {
        return self::create($this->decimal->mul(
            self::ensureCompatibleValue($value)
        ));
    }

    public function div($value): DecimalContract
    {
        return self::create($this->decimal->div(
            self::ensureCompatibleValue($value)
        ));
    }

    public function mod($value): DecimalContract
    {
        return self::create($this->decimal->mod(
            self::ensureCompatibleValue($value)
        ));
    }

    public function rem($value): DecimalContract
    {
        return self::create($this->decimal->rem(
            self::ensureCompatibleValue($value)
        ));
    }

    public function pow($exponent): DecimalContract
    {
        return self::create($this->decimal->pow(
            self::ensureCompatibleValue($exponent)
        ));
    }

    public function shift(int $places): DecimalContract
    {
        return self::create($this->decimal->shift($places));
    }

    public static function sum(iterable $values, int $precision = 28): DecimalContract
    {
        return self::create(PhpDecimal::sum(self::ensureCompatibleValues($values), $precision));
    }

    public static function avg(iterable $values, int $precision = 28): DecimalContract
    {
        return self::create(PhpDecimal::avg(self::ensureCompatibleValues($values), $precision));
    }

    private static function ensureCompatibleValue($value)
    {
        if ($value instanceof self) {
            return $value->decimal;
        }

        return $value;
    }

    private static function ensureCompatibleValues(iterable $values): array
    {
        $result = [];

        foreach ($values as $value) {
            $result[] = self::ensureCompatibleValue($value);
        }

        return $result;
    }
}
