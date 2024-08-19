<?php

namespace Shergela\Validations\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Shergela\Validations\Enums\ValidationRegexEnum as Regex;

class SeparateIntegersByComma implements ValidationRule
{
    /**
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /** @var string $toString */
        $toString = $value;

        if (! preg_match(pattern: Regex::SEPARATE_INTEGERS_BY_COMMA, subject: $toString)) {
            $fail("Please separate (:attribute) integer values by comma. Entered value: {$toString}");
        }
    }
}