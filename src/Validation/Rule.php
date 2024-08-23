<?php

namespace Shergela\Validations\Validation;

use BackedEnum;
use Closure;
use DateTimeZone;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Validator;
use Shergela\Validations\DataManipulation\InNotInRuleManipulation;
use Shergela\Validations\Enums\DatetimeZoneAbbreviationEnum;
use Shergela\Validations\Enums\ValidationDateEnum;
use Shergela\Validations\Enums\ValidationIntegerEnum as IntegerRule;
use Shergela\Validations\Enums\ValidationRuleEnum as RuleEnum;
use Shergela\Validations\Enums\ValidationStringEnum as StringRule;
use Shergela\Validations\Rules\LowercaseFirstLetter as LowerFL;
use Shergela\Validations\Rules\SeparateIntegersByComma as IntegerByComma;
use Shergela\Validations\Rules\SeparateStringsByComma as StringByComma;
use Shergela\Validations\Rules\SeparateStringsByUnderscore as StringByUnderscore;
use Shergela\Validations\Rules\TimezoneRegionValidation as TimezoneRegion;
use Shergela\Validations\Rules\TimezoneValidation as Timezone;
use Shergela\Validations\Rules\UppercaseFirstLetter as UpperFL;
use UnitEnum;

class Rule extends BuildValidationRule implements ValidationRule, ValidatorAwareRule, DataAwareRule
{
    /**
     * The validator performing the validation.
     *
     * @var Validator
     */
    protected Validator $validator;

    /**
     * @var array<string>
     */
    protected array $data = [];

    /**
     * The failure messages, if any.
     * @var array<string>
     */
    protected array $messages = [];

    /**
     * The array of custom error messages.
     * @var array<string>
     */
    protected array $customMessages = [];

    /**
     * @var array<string>
     */
    protected array $customAttributes = [];

    /**
     * Additional validation rules that should be merged into the default rules during validation.
     * @var array<string>
     */
    protected array $customRules = [];

    /**
     * @param Validator $validator
     * @return $this
     */
    public function setValidator(Validator $validator): static
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->passes(attribute: $attribute, value: $value) === false) {
            foreach ($this->messages as $message) {
                $fail($message);
            }
        }
    }

    /**
     * Set the data under validation.
     *
     * @param array<string> $data
     * @return $this
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return array<string>
     */
    protected function getValidationData(): array
    {
        return $this->data;
    }

    /**
     * @param array<string> $messages
     * @return Rule
     */
    public function messages(array $messages = []): Rule
    {
        $this->customMessages = $messages;

        return $this;
    }

    /**
     * @return Rule
     */
    public static function required(): Rule
    {
        static::$required = true;

        return new self();
    }

    /**
     * @return Rule
     */
    public static function nullable(): Rule
    {
        static::$nullable = true;

        return new self();
    }

    /**
     * @return $this
     */
    public function boolean(): static
    {
        static::$boolean = true;

        return $this;
    }

    /**
     * @param int $min
     * @return $this
     */
    public function min(int $min): static
    {
        $this->min = $min;

        return $this;
    }

    /**
     * @param int $max
     * @return $this
     */
    public function max(int $max): static
    {
        $this->max = $max;

        return $this;
    }

    /**
     * @return $this
     */
    public function email(): static
    {
        $this->email = true;

        return $this;
    }

    /**
     * @param string $table
     * @param string $column
     * @return $this
     */
    public function uniqueEmail(string $table, string $column): static
    {
        $this->email = true;
        $this->uniqueEmail = RuleEnum::UNIQUE_EMAIL . $table . ',' . $column;

        return $this;
    }

    /**
     * @param string|null $ascii
     * @return $this
     */
    public function alpha(string $ascii = null): static
    {
        if ($ascii !== null) {
            $this->alpha = StringRule::ALPHA . ':' . $ascii;

            return $this;
        }

        $this->alpha = StringRule::ALPHA;

        return $this;
    }

    /**
     * @param string|null $ascii
     * @return $this
     */
    public function alphaDash(string $ascii = null): static
    {
        if ($ascii !== null) {
            $this->alphaDash = StringRule::ALPHA_DASH . ':' . $ascii;

            return $this;
        }

        $this->alphaDash = StringRule::ALPHA_DASH;

        return $this;
    }

    /**
     * @param string|null $ascii
     * @return $this
     */
    public function alphaNum(string $ascii = null): static
    {
        if ($ascii !== null) {
            $this->alphaNum = StringRule::ALPHA_NUM . ':' . $ascii;

            return $this;
        }

        $this->alphaNum = StringRule::ALPHA_NUM;

        return $this;
    }

    /**
     * @return $this
     */
    public function string(): static
    {
        $this->string = true;

        return $this;
    }

    /**
     * @param array<string> $protocols
     * @return $this
     */
    public function url(array $protocols = []): static
    {
        if (!empty($protocols)) {
            $this->url = StringRule::URL . ':' . implode(',', $protocols);

            return $this;
        }

        $this->url = StringRule::URL;

        return $this;
    }

    /**
     * @return $this
     */
    public function uuid(): static
    {
        $this->uuid = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function ulid(): static
    {
        $this->ulid = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function integer(): static
    {
        $this->integer = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function numeric(): static
    {
        $this->numeric = true;

        return $this;
    }

    /**
     * @param int $digits
     * @return $this
     */
    public function digits(int $digits): static
    {
        $this->digits = IntegerRule::DIGITS . $digits;

        return $this;
    }

    /**
     * @param int $min
     * @param int $max
     * @return $this
     */
    public function digitsBetween(int $min, int $max): static
    {
        $this->digitsBetween = IntegerRule::DIGITS_BETWEEN . $min . ',' . $max;

        return $this;
    }

    /**
     * @return $this
     */
    public function date(): static
    {
        $this->date = true;

        return $this;
    }

    /**
     * @param string $date
     * @return $this
     */
    public function dateEquals(string $date): static
    {
        $this->dateEquals = $date;

        return $this;
    }

    /**
     * @param string $date
     * @return $this
     */
    public function dateBefore(string $date): static
    {
        $this->dateBefore = $date;

        return $this;
    }

    /**
     * @param string $date
     * @return $this
     */
    public function dateBeforeOrEqual(string $date): static
    {
        $this->dateBeforeOrEqual = $date;

        return $this;
    }

    /**
     * @param string $date
     * @return $this
     */
    public function dateAfter(string $date): static
    {
        $this->dateAfter = $date;

        return $this;
    }

    public function dateAfterOrEqualToday(): static
    {
        $this->dateAfterOrEqualToday = true;

        return $this;
    }

    /**
     * @param string $date
     * @return $this
     */
    public function dateAfterOrEquals(string $date): static
    {
        $this->dateAfterOrEqual = $date;

        return $this;
    }

    /**
     * @param string $format
     * @return $this
     */
    public function dateFormat(string $format): static
    {
        $this->dateFormat = $format;

        return $this;
    }

    /**
     * @param string|null $timezone
     * @return $this
     */
    public function timezone(string $timezone = null): static
    {
        if ($timezone !== null) {
            $this->timezone = ValidationDateEnum::TIMEZONE . $timezone;
            return $this;
        }

        $this->timezone = ValidationDateEnum::TIMEZONE_ALL;

        return $this;
    }

    /**
     * @param array<string> $timezones
     * @return $this
     */
    public function timezones(array $timezones): static
    {
        $this->timezones = $timezones;

        return $this;
    }

    /**
     * @param array<string> $cities
     * @return $this
     */
    public function timezoneAfrica(array $cities): static
    {
        $this->timezoneIdentifierCities = $this->collectCities(
            cities: $cities,
            timezone: DatetimeZoneAbbreviationEnum::AFRICA,
        );

        $this->dateTimezoneGroupNumber = DateTimeZone::AFRICA;
        $this->dateTimezoneGroupName = strtolower(DatetimeZoneAbbreviationEnum::AFRICA);

        return $this;
    }

    /**
     * @param array<string> $cities
     * @return $this
     */
    public function timezoneAsia(array $cities): static
    {
        $this->timezoneIdentifierCities = $this->collectCities(
            cities: $cities,
            timezone: DatetimeZoneAbbreviationEnum::ASIA,
        );

        $this->dateTimezoneGroupNumber = DateTimeZone::ASIA;
        $this->dateTimezoneGroupName = strtolower(DatetimeZoneAbbreviationEnum::ASIA);

        return $this;
    }

    /**
     * @param array<string>|string $cities
     * @return $this
     */
    public function timezoneEurope(array|string $cities): static
    {
        /** @var array<string> $cities */
        $cities = is_array($cities) ? $cities : func_get_args();

        $this->timezoneIdentifierCities = $this->collectCities(
            cities: $cities,
            timezone: DatetimeZoneAbbreviationEnum::EUROPE,
        );

        $this->dateTimezoneGroupNumber = DateTimeZone::EUROPE;
        $this->dateTimezoneGroupName = strtolower(DatetimeZoneAbbreviationEnum::EUROPE);

        return $this;
    }

    /**
     * @param array<string>|string $cities
     * @return $this
     */
    public function timezoneAmerica(array|string $cities): static
    {
        /** @var array<string> $cities */
        $cities = is_array($cities) ? $cities : func_get_args();

        $this->timezoneIdentifierCities = $this->collectCities(
            cities: $cities,
            timezone: DatetimeZoneAbbreviationEnum::AMERICA,
        );

        $this->dateTimezoneGroupNumber = DateTimeZone::AMERICA;
        $this->dateTimezoneGroupName = strtolower(DatetimeZoneAbbreviationEnum::AMERICA);

        return $this;
    }

    /**
     * @param array<string>|string $cities
     * @return $this
     */
    public function timezoneAntarctica(array|string $cities): static
    {
        /** @var array<string> $cities */
        $cities = is_array($cities) ? $cities : func_get_args();

        $this->timezoneIdentifierCities = $this->collectCities(
            cities: $cities,
            timezone: DatetimeZoneAbbreviationEnum::ANTARCTICA,
        );

        $this->dateTimezoneGroupNumber = DateTimeZone::ANTARCTICA;
        $this->dateTimezoneGroupName = strtolower(DatetimeZoneAbbreviationEnum::ANTARCTICA);

        return $this;
    }

    /**
     * @param array<string>|string $cities
     * @return $this
     */
    public function timezoneArctic(array|string $cities): static
    {
        /** @var array<string> $cities */
        $cities = is_array($cities) ? $cities : func_get_args();

        $this->timezoneIdentifierCities = $this->collectCities(
            cities: $cities,
            timezone: DatetimeZoneAbbreviationEnum::ARCTIC,
        );

        $this->dateTimezoneGroupNumber = DateTimeZone::ARCTIC;
        $this->dateTimezoneGroupName = strtolower(DatetimeZoneAbbreviationEnum::ARCTIC);

        return $this;
    }

    /**
     * @param array<string>|string $cities
     * @return $this
     */
    public function timezoneAtlantic(array|string $cities): static
    {
        /** @var array<string> $cities */
        $cities = is_array($cities) ? $cities : func_get_args();

        $this->timezoneIdentifierCities = $this->collectCities(
            cities: $cities,
            timezone: DatetimeZoneAbbreviationEnum::ATLANTIC,
        );

        $this->dateTimezoneGroupNumber = DateTimeZone::ATLANTIC;
        $this->dateTimezoneGroupName = strtolower(DatetimeZoneAbbreviationEnum::ATLANTIC);

        return $this;
    }

    /**
     * @param array<string>|string $cities
     * @return $this
     */
    public function timezoneAustralia(array|string $cities): static
    {
        /** @var array<string> $cities */
        $cities = is_array($cities) ? $cities : func_get_args();

        $this->timezoneIdentifierCities = $this->collectCities(
            cities: $cities,
            timezone: DatetimeZoneAbbreviationEnum::AUSTRALIA,
        );

        $this->dateTimezoneGroupNumber = DateTimeZone::AUSTRALIA;
        $this->dateTimezoneGroupName = strtolower(DatetimeZoneAbbreviationEnum::AUSTRALIA);

        return $this;
    }

    /**
     * @param array<string>|string $cities
     * @return $this
     */
    public function timezoneIndian(array|string $cities): static
    {
        /** @var array<string> $cities */
        $cities = is_array($cities) ? $cities : func_get_args();

        $this->timezoneIdentifierCities = $this->collectCities(
            cities: $cities,
            timezone: DatetimeZoneAbbreviationEnum::INDIAN,
        );

        $this->dateTimezoneGroupNumber = DateTimeZone::INDIAN;
        $this->dateTimezoneGroupName = strtolower(DatetimeZoneAbbreviationEnum::INDIAN);

        return $this;
    }

    /**
     * @param array<string>|string $cities
     * @return $this
     */
    public function timezonePacific(array|string $cities): static
    {
        /** @var array<string> $cities */
        $cities = is_array($cities) ? $cities : func_get_args();

        $this->timezoneIdentifierCities = $this->collectCities(
            cities: $cities,
            timezone: DatetimeZoneAbbreviationEnum::PACIFIC,
        );

        $this->dateTimezoneGroupNumber = DateTimeZone::PACIFIC;
        $this->dateTimezoneGroupName = strtolower(DatetimeZoneAbbreviationEnum::PACIFIC);

        return $this;
    }

    /**
     * @param int $first
     * @param int|null $second
     * @return $this
     */
    public function decimal(int $first, int $second = null): static
    {
        if ($second != null) {
            $this->decimal = IntegerRule::DECIMAL . $first . ',' . $second;
            return $this;
        }

        $this->decimal = IntegerRule::DECIMAL . $first;

        return $this;
    }

    /**
     * @return $this
     */
    public function uppercase(): static
    {
        $this->uppercase = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function uppercaseFirstLetter(): static
    {
        $this->uppercaseFirstLetter = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function lowercaseFirstLetter(): static
    {
        $this->lowercaseFirstLetter = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function lowercase(): static
    {
        $this->lowerCase = true;

        return $this;
    }

    /**
     * @param array<string> $with
     * @return $this
     */
    public function startsWith(array $with): static
    {
        $implode = implode(',', $with);

        $this->startsWith = StringRule::STARTS_WITH . $implode;

        return $this;
    }

    /**
     * @param array<string> $with
     * @return $this
     */
    public function endsWith(array $with): static
    {
        $implode = implode(',', $with);

        $this->endsWith = StringRule::ENDS_WITH . $implode;

        return $this;
    }

    /**
     * @param array<string> $with
     * @return $this
     */
    public function doesntStartWith(array $with): static
    {
        $implode = implode(',', $with);

        $this->doesntStartWith = StringRule::DOESNT_START_WITH . $implode;

        return $this;
    }

    /**
     * @param array<string> $with
     * @return $this
     */
    public function doesntEndWith(array $with): static
    {
        $implode = implode(',', $with);

        $this->doesntEndWith = StringRule::DOESNT_END_WITH . $implode;

        return $this;
    }

    /**
     * @param string $pattern
     * @return $this
     */
    public function regex(string $pattern): static
    {
        $this->regexPattern = $pattern;

        return $this;
    }

    /**
     * @return $this
     */
    public function separateIntegersByComma(): static
    {
        $this->separateIntegersByComma = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function separateStringsByComma(): static
    {
        $this->separateStringsByComma = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function separateStringsByUnderscore(): static
    {
        $this->separateStringsByUnderscore = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function hexColor(): static
    {
        $this->hexColor = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function ip(): static
    {
        $this->ip = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function ipv4(): static
    {
        $this->ipv4 = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function ipv6(): static
    {
        $this->ipv6 = true;

        return $this;
    }

    /**
     * @return $this
     * The field under validation must be a MAC address.
     */
    public function macAddress(): static
    {
        $this->macAddress = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function json(): static
    {
        $this->json = true;

        return $this;
    }

    /**
     * @return $this
     * The integer under validation must have a minimum length of value.
     */
    public function minDigits(int $minDigits): static
    {
        $this->minDigits = IntegerRule::MIN_DIGITS . $minDigits;

        return $this;
    }

    /**
     * @return $this
     * The integer under validation must have a maximum length of value.
     */
    public function maxDigits(int $maxDigits): static
    {
        $this->maxDigits = IntegerRule::MAX_DIGITS . $maxDigits;

        return $this;
    }

    /**
     * Specify additional validation rules that should be merged with the default rules during validation.
     * @param array<string> $rules
     * @return $this
     */
    public function rules(array $rules): static
    {
        $this->customRules = $rules;

        return $this;
    }

    /**
     * @param array<string> $attributes
     * @return $this
     */
    public function attributes(array $attributes): static
    {
        $this->customAttributes = $attributes;

        return $this;
    }


    /**
     * @param int $size
     * @return $this
     */
    public function size(int $size): static
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @param Arrayable<int, string>|array<string>|string $values
     * @return Rule
     */
    public static function in(Arrayable|array|string $values): Rule
    {
        if ($values instanceof Arrayable) {
            $values = $values->toArray();
        }

        /** @var Arrayable<int, string>|array<string>|string $values */
        $values = is_array($values) ? $values : func_get_args();

        static::$in = RuleEnum::IN . (new InNotInRuleManipulation(values: $values))->handle();

        return new self();
    }

    /**
     * @param Arrayable<int, string>|array<string>|string $values
     * @return Rule
     */
    public static function notIn(Arrayable|array|string $values): Rule
    {
        if ($values instanceof Arrayable) {
            $values = $values->toArray();
        }

        /** @var Arrayable<int, string>|array<string>|string $values */
        $values = is_array($values) ? $values : func_get_args();

        static::$notIn = RuleEnum::NOT_IN . (new InNotInRuleManipulation(values: $values))->handle();

        return new self();
    }

    /**
     * @return array<int,LowerFL|IntegerByComma|StringByComma|StringByUnderscore|TimezoneRegion|Timezone|UpperFL|string>
     */
    private function getValidationRules(): array
    {
        if (empty($this->customRules)) {
            return $this->buildValidationRules();
        }

        return array_merge($this->customRules, $this->buildValidationRules());
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes(string $attribute, mixed $value): bool
    {
        $validator = ValidatorFacade::make(
            data: $this->getValidationData(),
            rules: [$attribute => $this->getValidationRules()],
            messages: $this->customMessages,
            attributes: $this->customAttributes
        )->stopOnFirstFailure();


        if ($validator->fails()) {
            return $this->fail($validator->messages()->all());
        }

        return true;
    }

    /**
     * @return array<string>
     */
    public function getRules(): array
    {
        return $this->getValidationRules();
    }

    /**
     * @param int $key
     * @return string|null
     */
    public function getRule(int $key = 0): string|null
    {
        $rules = $this->getRules();

        /**
         * Get only one element of the array
         */
        if (isset($rules[$key])) {
            return $rules[$key];
        }

        return null;
    }

    /**
     * Adds the given failures, and return false.
     *
     * @param array<string>|string $messages
     * @return bool
     */
    protected function fail(array|string $messages): bool
    {
        $this->messages = array_merge($this->messages, Arr::wrap($messages));

        return false;
    }

    /**
     * @param array<string> $cities
     * @param string $timezone
     * @return array<string>
     */
    private function collectCities(array $cities, string $timezone): array
    {
        /** @var array<string> $cities */
        $cities = collect($cities)->map(function ($city) use ($timezone) {
            return strtolower($timezone . '/' . $city);
        })->toArray();

        return $cities;
    }
}
