<?php

namespace Shergela\Validations\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class LowercaseFirstLetter implements ValidationRule
{
    public function __construct(protected readonly ?string $message = null)
    {
    }

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

        if (Str::lcfirst($toString) !== $value) {
            $fail(
                $this->message !== null
                        ? $this->message
                        : 'The first character of :attribute must be lowercase letter.'
            );
        }
    }
}
