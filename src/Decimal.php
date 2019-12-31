<?php
/**
 * Contains the Decimal interface.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-12-31
 *
 */

namespace Vanilo\Contracts;

interface Decimal
{
    const DEFAULT_PRECISION = 28;

    /**
     * Returns the precision of the decimal instance.
     *
     * Precision defines the number of significant figures that a decimal is accurate to.
     */
    public function precision(): int;

    /**
     *  Returns TRUE if the decimal is not a defined number.
     */
    public function isNaN(): bool;

    /**
     * Returns TRUE if the decimal represents infinity, FALSE otherwise.
     */
    public function isInf(): bool;

    /**
     * Returns TRUE if the decimal is an integer, ie. does not have significant figures
     * behind the decimal point, otherwise FALSE.
     */
    public function isInteger(): bool;

    public function isZero(): bool;

    public function isPositive(): bool;

    public function isNegative(): bool;

    public function isEven(): bool;

    public function isOdd(): bool;

    /**
     * Returns the absolute (positive) value of the decimal.
     */
    public function abs(): Decimal;

    /**
     * Returns the same value as this decimal, but the sign inverted.
     */
    public function negate(): Decimal;

    /**
     * Returns the closest integer towards negative infinity.
     */
    public function floor(): Decimal;

    /**
     * Returns the closest integer towards positive infinity.
     */
    public function ceil(): Decimal;

    /**
     * Returns the result of discarding all digits behind the decimal point.
     */
    public function truncate(): Decimal;

    /**
     * Returns the value of the decimal with the same precision,
     * rounded according to the specified number of decimal
     * places and rounding mode.
     */
    public function round(int $places = 0, int $mode = PHP_ROUND_HALF_EVEN): Decimal;

    /**
     * Returns a copy of the decimal without trailing zeroes.
     */
    public function trim(): Decimal;

    /**
     * Returns the value of the decimal formatted to a fixed number of decimal
     * places, with thousands comma-separated, using a given rounding mode.
     */
    public function toFixed(int $places = 0, bool $commas = false, int $mode = PHP_ROUND_HALF_EVEN): string;

    /**
     * This method is equivalent to the == operator.
     */
    public function equals($other): bool;

    /**
     * This method is equivalent to the <=> operator.
     *
     *  Returns:
     *      0 if this decimal is considered equal to $other,
     *     -1 if this decimal should be placed before $other,
     *      1 if this decimal should be placed after $other.
     */
    public function compareTo($other): int;

    /**
     * This method is equivalent to a cast to int.
     */
    public function toInt(): int;

    /**
     * This method is equivalent to a cast to float.
     * Must not be affected by the 'precision' INI setting.
     */
    public function toFloat(): float;

    /**
     * This method is equivalent to a cast to string.
     */
    public function toString(): string;

    /**
     * This method is equivalent to the + operator.
     *
     * @param Decimal|string|int $value
     * @return Decimal
     */
    public function add($value): Decimal;

    /**
     * This method is equivalent to the - operator.
     *
     * @param Decimal|string|int $value
     * @return Decimal
     */
    public function sub($value): Decimal;

    /**
     * This method is equivalent to the * operator.
     *
     * @param Decimal|string|int $value
     * @return Decimal
     */
    public function mul($value): Decimal;

    /**
     * This method is equivalent to the / operator.
     *
     * @param Decimal|string|int $value
     * @return Decimal
     */
    public function div($value): Decimal;

    /**
     * This method is equivalent to the % operator.
     *
     * @param Decimal|string|int $value
     * @return Decimal
     */
    public function mod($value): Decimal;

    /**
     * Returns the remainder after dividing this decimal by a given value.
     *
     * @param Decimal|string|int $value
     * @return Decimal
     */
    public function rem($value): Decimal;

    /**
     * This method is equivalent to the ** operator.
     *
     * @param Decimal|string|int $value
     * @return Decimal
     */
    public function pow($exponent): Decimal;

    /**
     * Returns a copy of the decimal with its decimal place shifted.
     */
    public function shift(int $places): Decimal;

    /**
     * Creates a new Decimal instance
     *
     * @param Decimal|string|int $value
     * @param int                $precision
     *
     * @return Decimal
     */
    public static function create($value, int $precision = self::DEFAULT_PRECISION): Decimal;

    /**
     * Return the decimal sum of the given values
     *
     * This method is equivalent to adding each value individually.
     */
    public static function sum(iterable $values, int $precision = 28): Decimal;

    /**
     * Return the decimal average of the given values
     *
     * This method is equivalent to adding each value individually, then dividing by the number of values.
     */
    public static function avg(iterable $values, int $precision = 28): Decimal;
}
