<?php

namespace Shergela\Validations\Rules;

use Closure;
use DateTimeZone;
use Illuminate\Contracts\Validation\ValidationRule;

class TimezoneRegionValidation implements ValidationRule
{
    private ?string $message = null;

    /**
     * @param array<string> $cities
     */
    public function __construct(
        protected array $cities,
        protected int $timezoneGroupNumber,
        protected string $timezoneGroup,
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

        $timezonesList = $this->getTimezoneLists();

        /**
         * Validate cities if is valid for time zone regions.
         */
        if ($this->validateCities() === false) {
            $fail($this->message);
            return;
        };

        $timezonesList = collect($timezonesList)->map(function ($value) {
            return strtolower($value);
        })->toArray();

        if (! in_array($search, $timezonesList)) {
            $fail(
                sprintf(
                    "The city name [:attribute] (%s) is not in the valid timezone for (%s) list.",
                    ucfirst($toString),
                    ucfirst($this->timezoneGroup)
                )
            );
        }
    }

    /**
     * @return bool
     */
    private function validateCities(): bool
    {
        foreach ($this->cities as $city) {
            if (! in_array($city, $this->getTimezoneLists())) {
                $this->message = sprintf(
                    "This timezone [%s] is not in the valid timezone for [%s].",
                    ucfirst($city),
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
        return collect(DateTimeZone::listIdentifiers($this->timezoneGroupNumber))->map(
            function ($value) {
                return strtolower($value);
            }
        )->toArray();
    }
}
