<?php

namespace Shergela\Validations\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TimezoneValidation implements ValidationRule
{
    /**
     * @param array<string> $timezones
     */
    public function __construct(protected array $timezones)
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
            $fail("
                The [:attribute] value does not match the given timezones list: [{$implode}].
                Please provide correct timezone.
            ");
        }
    }
}
