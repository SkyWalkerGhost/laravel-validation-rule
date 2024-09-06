<?php

namespace Shergela\Validations\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;
use Shergela\Validations\Constants\ValidationRegex;

class LowercaseWord implements ValidationRule
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

        /** @var array<string> $pregSplit */
        $pregSplit = preg_split("/\s+/", $toString);

        $count = count($pregSplit);

        if (! preg_match(ValidationRegex::LETTERS_ONLY, $toString)) {
            $fail("The :attribute ($toString) must be a alphabetic word.");
            return;
        }

        if ($count > 1) {
            $fail("The :attribute ($toString) must be one lowercase word. ($count) words given.");
            return;
        }

        if (Str::lower($toString) !== $toString) {
            $fail($this->message === null ? "The :attribute ($toString) must be lowercase word." : $this->message);
        }
    }
}
