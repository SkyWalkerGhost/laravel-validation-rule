<?php

namespace Shergela\Validations\Rules;

use Closure;
use DateTimeZone;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class TimezoneRegionValidation implements ValidationRule
{
    private ?string $message = null;

    /**
     * @param array<string> $cities
     */
    public function __construct(
        protected readonly array $cities,
        protected readonly int $timezoneGroupNumber,
        protected readonly string $timezoneGroup,
        protected readonly string|null $customMessage,
    ) {
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

        $search = $this->timezoneGroup . '/' . $toString;

        /**
         * Validate provided cities
         */
        if ($this->validateProvidedValues() === false) {
            $fail($this->customMessage == null ? $this->message : $this->customMessage);
            return;
        }

        /**
         * Check if expected values match given values.
         */
        if (! in_array($search, $this->cities)) {
            if ($this->customMessage !== null) {
                $fail($this->customMessage);
            } else {
                $fail(sprintf("The city name [:attribute] (%s) is not selected correctly.", ucfirst($toString)));
            }
        }
    }

    /**
     * @return bool
     */
    private function validateProvidedValues(): bool
    {
        foreach ($this->cities as $city) {
            if (! in_array($city, $this->getTimezoneLists())) {
                $after = ucfirst(Str::after($city, '/'));
                $this->message = sprintf(
                    "This timezone (:attribute) [%s] is not in the valid timezone for %s.",
                    ucfirst($this->timezoneGroup . '/' . $after),
                    ucfirst($this->timezoneGroup)
                );
                return false;
            }
        }
        return true;
    }

    /**
     * @return array<string>
     */
    private function getTimezoneLists(): array
    {
        /** @var array<string> $list */
        $list = collect(DateTimeZone::listIdentifiers($this->timezoneGroupNumber))->map(
            function ($value) {
                return strtolower($value);
            }
        )->toArray();

        return $list;
    }
}
