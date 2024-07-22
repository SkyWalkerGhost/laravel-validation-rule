<?php

namespace Shergela\Validations\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class UppercaseFirstLetter implements ValidationRule
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

        if (is_numeric($toString)) {
            $fail('The :attribute must be a alphabetical string.');
        }

        if (Str::ucfirst($toString) !== $value) {
            $fail('The first character of :attribute must be uppercase.');
        }
    }
}