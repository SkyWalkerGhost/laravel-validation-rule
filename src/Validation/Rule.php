<?php

namespace Shergela\Validations\Validation;

use Closure;
use DateTimeZone;
use Exception;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use Shergela\Validations\DataManipulation\InNotInRuleManipulation;
use Shergela\Validations\Constants\DatetimeZoneAbbreviation;
use Shergela\Validations\Constants\IPMacValidation;
use Shergela\Validations\Constants\ValidationDate;
use Shergela\Validations\Constants\ValidationDate as DateRule;
use Shergela\Validations\Constants\ValidationInteger as IntegerRule;
use Shergela\Validations\Constants\ValidationRule as RuleConst;
use Shergela\Validations\Constants\ValidationString as StringRule;

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
    protected static array $customMessages = [];

    /**
     * @var array<string>
     */
    protected static array $customValidationMessages = [];

    /**
     * @var array<string>
     */
    protected array $customAttributes = [];

    /**
     * Additional validation rules that should be merged into the default rules during validation.
     * @var array<string>
     */
    protected array $customRules = [];

    private static string $placeholder = ':';

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
     * @throws Exception
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
     * @param string $message
     * @param string $rule
     * @return string
     * @throws Exception
     */
    private static function buildCustomMessage(string $message, string $rule): string
    {
        if (Str::contains($message, self::$placeholder) === false) {
            throw new Exception(
                'The placeholder not defined in the message.'
                . ' ' . 'Placeholder must be like this [:name] format.'
                . ' ' . 'For example `The :name field is required.`'
            );
        }

        $explode = explode(' ', Str::after($message, self::$placeholder));

        return $explode[0] . '.' . $rule;
    }

    /**
     * @param array<string> $messages
     * @return Rule
     */
    public function messages(array $messages = []): Rule
    {
        static::$customValidationMessages = $messages;

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
     * @param string|null $message
     * @return Rule
     * @throws Exception
     */
    public static function required(string $message = null): Rule
    {
        static::$required = true;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: 'required')] = $message;
        }

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
     * @throws Exception
     */
    public function boolean(string $message = null): static
    {
        static::$boolean = true;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: RuleConst::BOOLEAN)] = $message;
        }

        return $this;
    }

    /**
     * @param int $min
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function min(int $min, string $message = null): static
    {
        $this->min = $min;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: 'min')] = $message;
        }

        return $this;
    }

    /**
     * @param int $max
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function max(int $max, string $message = null): static
    {
        $this->max = $max;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: 'max')] = $message;
        }

        return $this;
    }

    /**
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function email(string $message = null): static
    {
        $this->email = true;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: RuleConst::EMAIL)] = $message;
        }

        return $this;
    }

    /**
     * @param string $table
     * @param string $column
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function uniqueEmail(string $table, string $column, string $message = null): static
    {
        $this->email = true;
        $this->uniqueEmail = RuleConst::UNIQUE_EMAIL . $table . ',' . $column;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: 'unique')] = $message;
        }

        return $this;
    }

    /**
     * @param bool $ascii
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function alpha(bool $ascii = false, string $message = null): static
    {
        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: StringRule::ALPHA)] = $message;
        }

        if ($ascii === true) {
            $this->alpha = StringRule::ALPHA . ':' . StringRule::ASCII;

            return $this;
        }

        $this->alpha = StringRule::ALPHA;

        return $this;
    }

    /**
     * @param bool $ascii
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function alphaDash(bool $ascii = false, string $message = null): static
    {
        if ($message !== null) {
            static::$customMessages[
                self::buildCustomMessage(
                    message: $message,
                    rule: StringRule::ALPHA_DASH
                )
            ] = $message;
        }

        if ($ascii === true) {
            $this->alphaDash = StringRule::ALPHA_DASH . ':' . StringRule::ASCII;

            return $this;
        }

        $this->alphaDash = StringRule::ALPHA_DASH;

        return $this;
    }

    /**
     * @param bool $ascii
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function alphaNum(bool $ascii = false, string $message = null): static
    {
        if ($message !== null) {
            static::$customMessages[
                self::buildCustomMessage(message: $message, rule: StringRule::ALPHA_NUM)
            ] = $message;
        }

        if ($ascii === true) {
            $this->alphaNum = StringRule::ALPHA_NUM . ':' . StringRule::ASCII;

            return $this;
        }

        $this->alphaNum = StringRule::ALPHA_NUM;

        return $this;
    }

    /**
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function string(string $message = null): static
    {
        $this->string = true;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: StringRule::STRING)] = $message;
        }

        return $this;
    }

    /**
     * @param array<string> $protocols
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function url(array $protocols = [], string $message = null): static
    {
        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: StringRule::URL)] = $message;
        }

        if (!empty($protocols)) {
            $this->url = StringRule::URL . ':' . implode(',', $protocols);

            return $this;
        }

        $this->url = StringRule::URL;

        return $this;
    }

    /**
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function uuid(string $message = null): static
    {
        $this->uuid = true;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: RuleConst::UUID)] = $message;
        }

        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function ulid(string $message = null): static
    {
        $this->ulid = true;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: RuleConst::ULID)] = $message;
        }

        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function integer(string $message = null): static
    {
        $this->integer = true;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: IntegerRule::INTEGER)] = $message;
        }

        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function numeric(string $message = null): static
    {
        $this->numeric = true;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: IntegerRule::NUMERIC)] = $message;
        }

        return $this;
    }

    /**
     * @param int $digits
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function digits(int $digits, string $message = null): static
    {
        $this->digits = IntegerRule::DIGITS . $digits;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: 'digits')] = $message;
        }

        return $this;
    }

    /**
     * @param int $min
     * @param int $max
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function digitsBetween(int $min, int $max, string $message = null): static
    {
        $this->digitsBetween = IntegerRule::DIGITS_BETWEEN . $min . ',' . $max;

        if ($message !== null) {
            static::$customMessages['digits_between'] = $message;
            static::$customMessages[self::buildCustomMessage(message: $message, rule: 'digits_between')] = $message;
        }

        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function date(string $message = null): static
    {
        $this->date = true;

        if ($message !== null) {
            static::$customMessages[DateRule::DATE] = $message;
            static::$customMessages[self::buildCustomMessage(message: $message, rule: DateRule::DATE)] = $message;
        }

        return $this;
    }

    /**
     * @param string $date
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function dateEquals(string $date, string $message = null): static
    {
        $this->dateEquals = $date;

        if ($message !== null) {
            static::$customMessages['date_equals'] = $message;
            static::$customMessages[self::buildCustomMessage(message: $message, rule: 'date_equals')] = $message;
        }

        return $this;
    }

    /**
     * @param string $date
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function dateBefore(string $date, string $message = null): static
    {
        $this->dateBefore = $date;

        if ($message !== null) {
            static::$customMessages['before'] = $message;
            static::$customMessages[self::buildCustomMessage(message: $message, rule: 'before')] = $message;
        }

        return $this;
    }

    /**
     * @param string $date
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function dateBeforeOrEqual(string $date, string $message = null): static
    {
        $this->dateBeforeOrEqual = $date;

        if ($message !== null) {
            static::$customMessages['before_or_equal'] = $message;
            static::$customMessages[self::buildCustomMessage(message: $message, rule: 'before_or_equal')] = $message;
        }

        return $this;
    }

    /**
     * @param string $date
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function dateAfter(string $date, string $message = null): static
    {
        $this->dateAfter = $date;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: 'after')] = $message;
        }

        return $this;
    }

    /**
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function dateAfterOrEqualToday(string $message = null): static
    {
        $this->dateAfterOrEqualToday = true;

        if ($message !== null) {
            static::$customMessages[
                self::buildCustomMessage(message: $message, rule: DateRule::DATE_AFTER_OR_EQUAL_TODAY)
            ] = $message;
        }

        return $this;
    }

    /**
     * @param string $date
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function dateAfterOrEquals(string $date, string $message = null): static
    {
        $this->dateAfterOrEqual = $date;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: 'after_or_equal')] = $message;
        }

        return $this;
    }

    /**
     * @param string $format
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function dateFormat(string $format, string $message = null): static
    {
        $this->dateFormat = $format;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: 'date_format')] = $message;
        }

        return $this;
    }

    /**
     * @param string|null $timezone
     * @return $this
     * @throws Exception
     */
    public function timezone(string $timezone = null, string $message = null): static
    {
        if ($message !== null) {
            static::$customMessages[
                self::buildCustomMessage(message: $message, rule: ValidationDate::TIMEZONE)
            ] = $message;
        }

        if ($timezone !== null) {
            $this->timezone = ValidationDate::TIMEZONE . ':' . $timezone;
            return $this;
        }

        $this->timezone = ValidationDate::TIMEZONE_ALL;

        return $this;
    }

    /**
     * @param array<string>|string $timezones
     * @return $this
     */
    public function timezones(array|string $timezones, string $message = null): static
    {
        /** @var array<string> $timezones */
        $timezones = is_array($timezones) ? $timezones : func_get_args();

        $this->timezones = $timezones;

        if ($message !== null) {
            $this->timezoneValidationMessage = $message;
        }

        return $this;
    }

    /**
     * @param array<string>|string $cities
     * @return $this
     */
    public function timezoneAfrica(array|string $cities, string $message = null): static
    {
        /** @var array<string> $cities */
        $cities = is_array($cities) ? $cities : func_get_args();

        $this->timezoneIdentifierCities = $this->collectCities(
            cities: $cities,
            timezone: DatetimeZoneAbbreviation::AFRICA,
        );

        $this->dateTimezoneGroupNumber = DateTimeZone::AFRICA;
        $this->dateTimezoneGroupName = strtolower(DatetimeZoneAbbreviation::AFRICA);

        if ($message !== null) {
            $this->timezoneValidationMessage = $message;
        }

        return $this;
    }

    /**
     * @param array<string>|string $cities
     * @param string|null $message
     * @return $this
     */
    public function timezoneAsia(array|string $cities, string $message = null): static
    {
        /** @var array<string> $cities */
        $cities = is_array($cities) ? $cities : func_get_args();

        $this->timezoneIdentifierCities = $this->collectCities(
            cities: $cities,
            timezone: DatetimeZoneAbbreviation::ASIA,
        );

        $this->dateTimezoneGroupNumber = DateTimeZone::ASIA;
        $this->dateTimezoneGroupName = strtolower(DatetimeZoneAbbreviation::ASIA);

        if ($message !== null) {
            $this->timezoneValidationMessage = $message;
        }

        return $this;
    }

    /**
     * @param array<string>|string $cities
     * @param string|null $message
     * @return $this
     */
    public function timezoneEurope(array|string $cities, string $message = null): static
    {
        /** @var array<string> $cities */
        $cities = is_array($cities) ? $cities : func_get_args();

        $this->timezoneIdentifierCities = $this->collectCities(
            cities: $cities,
            timezone: DatetimeZoneAbbreviation::EUROPE,
        );

        $this->dateTimezoneGroupNumber = DateTimeZone::EUROPE;
        $this->dateTimezoneGroupName = strtolower(DatetimeZoneAbbreviation::EUROPE);

        if ($message !== null) {
            $this->timezoneValidationMessage = $message;
        }

        return $this;
    }

    /**
     * @param array<string>|string $cities
     * @param string|null $message
     * @return $this
     */
    public function timezoneAmerica(array|string $cities, string $message = null): static
    {
        /** @var array<string> $cities */
        $cities = is_array($cities) ? $cities : func_get_args();

        $this->timezoneIdentifierCities = $this->collectCities(
            cities: $cities,
            timezone: DatetimeZoneAbbreviation::AMERICA,
        );

        $this->dateTimezoneGroupNumber = DateTimeZone::AMERICA;
        $this->dateTimezoneGroupName = strtolower(DatetimeZoneAbbreviation::AMERICA);

        if ($message !== null) {
            $this->timezoneValidationMessage = $message;
        }

        return $this;
    }

    /**
     * @param array<string>|string $cities
     * @param string|null $message
     * @return $this
     */
    public function timezoneAntarctica(array|string $cities, string $message = null): static
    {
        /** @var array<string> $cities */
        $cities = is_array($cities) ? $cities : func_get_args();

        $this->timezoneIdentifierCities = $this->collectCities(
            cities: $cities,
            timezone: DatetimeZoneAbbreviation::ANTARCTICA,
        );

        $this->dateTimezoneGroupNumber = DateTimeZone::ANTARCTICA;
        $this->dateTimezoneGroupName = strtolower(DatetimeZoneAbbreviation::ANTARCTICA);

        if ($message !== null) {
            $this->timezoneValidationMessage = $message;
        }

        return $this;
    }

    /**
     * @param array<string>|string $cities
     * @param string|null $message
     * @return $this
     */
    public function timezoneArctic(array|string $cities, string $message = null): static
    {
        /** @var array<string> $cities */
        $cities = is_array($cities) ? $cities : func_get_args();

        $this->timezoneIdentifierCities = $this->collectCities(
            cities: $cities,
            timezone: DatetimeZoneAbbreviation::ARCTIC,
        );

        $this->dateTimezoneGroupNumber = DateTimeZone::ARCTIC;
        $this->dateTimezoneGroupName = strtolower(DatetimeZoneAbbreviation::ARCTIC);

        if ($message !== null) {
            $this->timezoneValidationMessage = $message;
        }

        return $this;
    }

    /**
     * @param array<string>|string $cities
     * @param string|null $message
     * @return $this
     */
    public function timezoneAtlantic(array|string $cities, string $message = null): static
    {
        /** @var array<string> $cities */
        $cities = is_array($cities) ? $cities : func_get_args();

        $this->timezoneIdentifierCities = $this->collectCities(
            cities: $cities,
            timezone: DatetimeZoneAbbreviation::ATLANTIC,
        );

        $this->dateTimezoneGroupNumber = DateTimeZone::ATLANTIC;
        $this->dateTimezoneGroupName = strtolower(DatetimeZoneAbbreviation::ATLANTIC);

        if ($message !== null) {
            $this->timezoneValidationMessage = $message;
        }

        return $this;
    }

    /**
     * @param array<string>|string $cities
     * @param string|null $message
     * @return $this
     */
    public function timezoneAustralia(array|string $cities, string $message = null): static
    {
        /** @var array<string> $cities */
        $cities = is_array($cities) ? $cities : func_get_args();

        $this->timezoneIdentifierCities = $this->collectCities(
            cities: $cities,
            timezone: DatetimeZoneAbbreviation::AUSTRALIA,
        );

        $this->dateTimezoneGroupNumber = DateTimeZone::AUSTRALIA;
        $this->dateTimezoneGroupName = strtolower(DatetimeZoneAbbreviation::AUSTRALIA);

        if ($message !== null) {
            $this->timezoneValidationMessage = $message;
        }

        return $this;
    }

    /**
     * @param array<string>|string $cities
     * @param string|null $message
     * @return $this
     */
    public function timezoneIndian(array|string $cities, string $message = null): static
    {
        /** @var array<string> $cities */
        $cities = is_array($cities) ? $cities : func_get_args();

        $this->timezoneIdentifierCities = $this->collectCities(
            cities: $cities,
            timezone: DatetimeZoneAbbreviation::INDIAN,
        );

        $this->dateTimezoneGroupNumber = DateTimeZone::INDIAN;
        $this->dateTimezoneGroupName = strtolower(DatetimeZoneAbbreviation::INDIAN);

        if ($message !== null) {
            $this->timezoneValidationMessage = $message;
        }

        return $this;
    }

    /**
     * @param array<string>|string $cities
     * @param string|null $message
     * @return $this
     */
    public function timezonePacific(array|string $cities, string $message = null): static
    {
        /** @var array<string> $cities */
        $cities = is_array($cities) ? $cities : func_get_args();

        $this->timezoneIdentifierCities = $this->collectCities(
            cities: $cities,
            timezone: DatetimeZoneAbbreviation::PACIFIC,
        );

        $this->dateTimezoneGroupNumber = DateTimeZone::PACIFIC;
        $this->dateTimezoneGroupName = strtolower(DatetimeZoneAbbreviation::PACIFIC);

        if ($message !== null) {
            $this->timezoneValidationMessage = $message;
        }

        return $this;
    }

    /**
     * @param int $first
     * @param int|null $second
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function decimal(int $first, int $second = null, string $message = null): static
    {
        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: 'decimal')] = $message;
        }

        if ($second != null) {
            $this->decimal = IntegerRule::DECIMAL . $first . ',' . $second;
            return $this;
        }

        $this->decimal = IntegerRule::DECIMAL . $first;

        return $this;
    }

    /**
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function uppercase(string $message = null): static
    {
        $this->uppercase = true;

        if ($message !== null) {
            static::$customMessages[
                self::buildCustomMessage(message: $message, rule: StringRule::UPPERCASE)
            ] = $message;
        }

        return $this;
    }

    /**
     * @param string|null $message
     * @return $this
     */
    public function uppercaseFirstLetter(string $message = null): static
    {
        $this->uppercaseFirstLetter = true;

        if ($message !== null) {
            $this->validationMessage = $message;
        }

        return $this;
    }

    /**
     * @param string|null $message
     * @return $this
     */
    public function lowercaseFirstLetter(string $message = null): static
    {
        $this->lowercaseFirstLetter = true;

        if ($message !== null) {
            $this->validationMessage = $message;
        }

        return $this;
    }

    /**
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function lowercase(string $message = null): static
    {
        $this->lowerCase = true;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: 'lowercase')] = $message;
        }

        return $this;
    }

    /**
     * @param string|null $message
     * @return $this
     */
    public function uppercaseWord(string $message = null): static
    {
        $this->uppercaseWord = true;

        if ($message !== null) {
            $this->validationMessage = $message;
        }

        return $this;
    }

    /**
     * @param string|null $message
     * @return $this
     */
    public function lowercaseWord(string $message = null): static
    {
        $this->lowerCaseWord = true;

        if ($message !== null) {
            $this->validationMessage = $message;
        }

        return $this;
    }

    /**
     * @param string|null $message
     * @return $this
     */
    public function lettersAndSpaces(string $message = null): static
    {
        $this->onlyLettersAndSpaces = true;

        if ($message !== null) {
            $this->validationMessage = $message;
        }

        return $this;
    }

    /**
     * @param array<string> $with
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function startsWith(array $with, string $message = null): static
    {
        $implode = implode(',', $with);

        $this->startsWith = StringRule::STARTS_WITH . $implode;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: 'starts_with')] = $message;
        }

        return $this;
    }

    /**
     * @param array<string> $with
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function endsWith(array $with, string $message = null): static
    {
        $implode = implode(',', $with);

        $this->endsWith = StringRule::ENDS_WITH . $implode;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: 'ends_with')] = $message;
        }

        return $this;
    }

    /**
     * @param array<string> $with
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function doesntStartWith(array $with, string $message = null): static
    {
        $implode = implode(',', $with);

        $this->doesntStartWith = StringRule::DOESNT_START_WITH . $implode;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: 'doesnt_start_with')] = $message;
        }

        return $this;
    }

    /**
     * @param array<string> $with
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function doesntEndWith(array $with, string $message = null): static
    {
        $implode = implode(',', $with);

        $this->doesntEndWith = StringRule::DOESNT_END_WITH . $implode;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: 'doesnt_end_with')] = $message;
        }

        return $this;
    }

    /**
     * @param string $pattern
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function regex(string $pattern, string $message = null): static
    {
        $this->regexPattern = $pattern;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: 'regex')] = $message;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function separateIntegersByComma(string $message = null): static
    {
        $this->separateIntegersByComma = true;

        if ($message !== null) {
            $this->validationMessage = $message;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function separateStringsByComma(string $message = null): static
    {
        $this->separateStringsByComma = true;

        if ($message !== null) {
            $this->validationMessage = $message;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function separateStringsByUnderscore(string $message = null): static
    {
        $this->separateStringsByUnderscore = true;

        if ($message !== null) {
            $this->validationMessage = $message;
        }

        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function hexColor(string $message = null): static
    {
        $this->hexColor = true;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: RuleConst::HEX_COLOR)] = $message;
        }

        return $this;
    }

    /**
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function ip(string $message = null): static
    {
        $this->ip = true;

        if ($message !== null) {
            static::$customMessages[
                self::buildCustomMessage(message: $message, rule: IPMacValidation::IP)
            ] = $message;
        }

        return $this;
    }

    /**
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function ipv4(string $message = null): static
    {
        $this->ipv4 = true;

        if ($message !== null) {
            static::$customMessages[
                self::buildCustomMessage(message: $message, rule: IPMacValidation::IPV4)
            ] = $message;
        }

        return $this;
    }

    /**
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function ipv6(string $message = null): static
    {
        if ($message !== null) {
            static::$customMessages[
                self::buildCustomMessage(message: $message, rule: IPMacValidation::IPV6)
            ] = $message;
        }

        return $this;
    }

    /**
     * @param string|null $message
     * @return $this
     * The field under validation must be a MAC address.
     * @throws Exception
     */
    public function macAddress(string $message = null): static
    {
        $this->macAddress = true;

        if ($message !== null) {
            static::$customMessages[
                self::buildCustomMessage(message: $message, rule: IPMacValidation::MAC_ADDRESS)
            ] = $message;
        }

        return $this;
    }

    /**
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function json(string $message = null): static
    {
        $this->json = true;

        if ($message !== null) {
            static::$customMessages[
                self::buildCustomMessage(message: $message, rule: StringRule::JSON)
            ] = $message;
        }

        return $this;
    }

    /**
     * @param string|null $message
     * @return $this
     * The integer under validation must have a minimum length of value.
     * @throws Exception
     */
    public function minDigits(int $minDigits, string $message = null): static
    {
        $this->minDigits = IntegerRule::MIN_DIGITS . $minDigits;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: 'min_digits')] = $message;
        }

        return $this;
    }

    /**
     * @param string|null $message
     * @return $this
     * The integer under validation must have a maximum length of value.
     * @throws Exception
     */
    public function maxDigits(int $maxDigits, string $message = null): static
    {
        $this->maxDigits = IntegerRule::MAX_DIGITS . $maxDigits;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: 'max_digits')] = $message;
        }

        return $this;
    }


    /**
     * @param int $size
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function size(int $size, string $message = null): static
    {
        $this->size = $size;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: 'size')] = $message;
        }

        return $this;
    }

    /**
     * @param Arrayable<int, string>|array<string>|string $values
     * @param string|null $message
     * @return Rule
     * @throws Exception
     */
    public static function in(Arrayable|array|string $values, string $message = null): Rule
    {
        if ($values instanceof Arrayable) {
            $values = $values->toArray();
        }

        /** @var Arrayable<int, string>|array<string>|string $values */
        $values = is_array($values) ? $values : func_get_args();

        static::$in = (new InNotInRuleManipulation(values: $values, in: true))->handle();

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: RuleConst::IN)] = $message;
        }

        return new self();
    }

    /**
     * @param Arrayable<int, string>|array<string>|string $values
     * @param string|null $message
     * @return Rule
     * @throws Exception
     */
    public static function notIn(Arrayable|array|string $values, string $message = null): Rule
    {
        if ($values instanceof Arrayable) {
            $values = $values->toArray();
        }

        /** @var Arrayable<int, string>|array<string>|string $values */
        $values = is_array($values) ? $values : func_get_args();

        static::$notIn = (new InNotInRuleManipulation(values: $values, notIn: true))->handle();

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: RuleConst::NOT_IN)] = $message;
        }

        return new self();
    }

    /**
     * @param int $length
     * @param string|null $message
     * @return $this
     * @throws Exception
     */
    public function length(int $length, string $message = null): static
    {
        $this->size = $length;

        if ($message !== null) {
            static::$customMessages[self::buildCustomMessage(message: $message, rule: 'size')] = $message;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function array(): static
    {
        $this->array = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function arrayDistinct(): static
    {
        $this->arrayDistinct = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function arrayDistinctStrict(): static
    {
        $this->distinctStrict = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function arrayDistinctIgnoreCase(): static
    {
        $this->distinctIgnoreCase = true;

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
     * @return array<string>
     */
    private function getValidationRules(): array
    {
        /** @var array<string> $rules */
        $rules = $this->buildValidationRules();

        if (empty($this->customRules)) {
            return $rules;
        }

        return array_merge($this->customRules, $rules);
    }

    /**
     * @return array<string>
     */
    private function getValidationMessages(): array
    {
        $result = static::$customValidationMessages;

        if (! empty(static::$customMessages)) {
            foreach (static::$customMessages as $index => $msg) {
                $placeholder = Str::replace(self::$placeholder, '', $msg);
                $replace = Str::replace('_', ' ', $placeholder);

                $result[$index] = $replace;
            }
        }

        return $result;
    }

    /**
     * @param string $attribute
     * @return string
     */
    private function getAttribute(string $attribute): string
    {
        return Str::contains($attribute, '.') === true ? Str::before($attribute, '.') . '.*' : $attribute;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @throws Exception
     */
    public function passes(string $attribute, mixed $value): bool
    {
        $rules = [
            $this->getAttribute(attribute: $attribute) => $this->getValidationRules()
        ];

        $validator = ValidatorFacade::make(
            data: $this->getValidationData(),
            rules: $rules,
            messages: $this->getValidationMessages(),
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
        /** @var array<string> $rules */
        $rules = $this->getValidationRules();

        return $rules;
    }

    /**
     * @param int $key
     * @return string|null
     */
    public function getRule(int $key = 0): string|null
    {
        /** @var array<string> $rules */
        $rules = $this->getRules();

        /**
         * Get only one element of the array by default.
         */
        if (isset($rules[$key])) {
            return $rules[$key];
        }

        return null;
    }

    /**
     * @return void
     */
    public function ddGetRules(): void
    {
        dd($this->getRules());
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
