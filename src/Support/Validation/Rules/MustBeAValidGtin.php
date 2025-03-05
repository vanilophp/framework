<?php

declare(strict_types=1);

namespace Vanilo\Support\Validation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Vanilo\Support\Validation\GtinValidator;

class MustBeAValidGtin implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) && !is_int($value)) {
            $fail(__('The :attribute must be a numeric string'));

            return;
        }

        if (!GtinValidator::isValid($value)) {
            $fail(__('The :attribute must be a valid Global Trade Item Number (GTIN) [8, 12, 13 or 14 digits with a valid check digit]'));
        }
    }
}
