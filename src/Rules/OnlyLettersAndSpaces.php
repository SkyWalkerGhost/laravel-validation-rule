<?php

namespace Shergela\Validations\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Shergela\Validations\Constants\ValidationRegex;

class OnlyLettersAndSpaces implements ValidationRule
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

        $message = $this->message === null
            ? "The :attribute ($toString) must contain only letters and spaces."
            : $this->message;

        if (! preg_match(ValidationRegex::ONLY_LETTERS_AND_SPACES, $toString)) {
            $fail($message);
        }
    }
}
