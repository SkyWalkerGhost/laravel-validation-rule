<?php

namespace Shergela\Validations\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Shergela\Validations\Enums\ValidationRegexEnum as Regex;

class SeparateStringsByComma implements ValidationRule
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

        if (! preg_match(pattern: Regex::SEPARATE_STRINGS_BY_COMMA, subject: $toString)) {
            $fail("Please separate (:attribute) values by comma. Entered value: {$toString}");
        }
    }
}
