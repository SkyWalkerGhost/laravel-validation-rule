<?php

namespace Shergela\Validations\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class TimezoneValidation implements ValidationRule
{
    /**
     * @param array<string> $timezones
     */
    public function __construct(protected readonly array $timezones, protected readonly ?string $message = null)
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

        $toString = strtolower($toString);

        $timezones = collect($this->timezones)->map(function ($value) {
            return strtolower($value);
        })->toArray();

        if (! in_array($toString, $timezones)) {
            $implode = implode(', ', $this->timezones);
            if ($this->message !== null) {
                $fail($this->message);
            } else {
                $fail(sprintf("
                The :attribute value (%s) does not match the given timezones list: [%s].
                    Please provide correct timezone.
                ", $toString, $implode));
            }
        }
    }
}
