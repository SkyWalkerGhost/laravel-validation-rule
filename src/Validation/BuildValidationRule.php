<?php

namespace Shergela\Validations\Validation;

use Shergela\Validations\Constants\IPMacValidation as IPMACRule;
use Shergela\Validations\Constants\ValidationDate as DateRule;
use Shergela\Validations\Constants\ValidationInteger as IntegerRule;
use Shergela\Validations\Constants\ValidationRegex as RegexRule;
use Shergela\Validations\Constants\ValidationRule as ShergelaRule;
use Shergela\Validations\Constants\ValidationString as StringRule;
use Shergela\Validations\Rules\LowercaseFirstLetter;
use Shergela\Validations\Rules\LowercaseWord;
use Shergela\Validations\Rules\OnlyLettersAndSpaces;
use Shergela\Validations\Rules\SeparateIntegersByComma as IntegerByComma;
use Shergela\Validations\Rules\SeparateStringsByComma as StringByComma;
use Shergela\Validations\Rules\SeparateStringsByUnderscore as StringByUnderscore;
use Shergela\Validations\Constants\ValidationArray as ArrayRule;
use Shergela\Validations\Rules\TimezoneRegionValidation;
use Shergela\Validations\Rules\TimezoneValidation;
use Shergela\Validations\Rules\UppercaseFirstLetter;
use Shergela\Validations\Rules\UppercaseWord;

class BuildValidationRule
{
    /**
     * @var bool
     */
    protected static bool $required = false;

    /**
     * @var bool
     */
    protected static bool $nullable = false;

    /**
     * @var bool
     */
    protected static bool $boolean = false;

    /**
     * @var string|null
     * Set custom message
     */
    protected ?string $validationMessage = null;
    protected ?string $timezoneValidationMessage = null;

    /**
     * @var int|null
     */
    protected ?int $min = null;

    /**
     * @var int|null
     */
    protected ?int $max = null;

    /**
     * @var bool
     */
    protected bool $email = false;

    /**
     * @var string|null
     */
    protected ?string $uniqueEmail = null;

    /**
     * @var string
     */
    protected string $table;

    /**
     * @var string
     */
    protected string $column;

    /**
     * @var bool
     */
    protected bool $string = false;

    /**
     * @var string|null
     * The field under validation must start with one of the given values starts_with:foo,bar.
     */
    protected ?string $startsWith = null;

    /**
     * @var string|null
     * The field under validation must end with one of the given values ends_with:foo,bar
     */
    protected ?string $endsWith = null;

    /**
     * @var string|null
     * The field under validation must not start with one of the given values doesnt_start_with:foo,bar
     */
    protected ?string $doesntStartWith = null;

    /**
     * @var string|null
     * The field under validation must not start with one of the given values doesnt_end_with:foo,bar
     */
    protected ?string $doesntEndWith = null;

    /**
     * @var bool
     */
    protected bool $integer = false;

    /**
     * @var bool
     */
    protected bool $numeric = false;

    /**
     * @var string|null
     */
    protected ?string $decimal = null;

    /**
     * @var string|null
     */
    protected ?string $digits = null;

    /**
     * @var string|null
     */
    protected ?string $digitsBetween = null;

    /**
     * @var string|null
     */
    protected ?string $alpha = null;

    /**
     * @var string|null
     */
    protected ?string $alphaDash = null;

    /**
     * @var string|null
     */
    protected ?string $alphaNum = null;

    /**
     * @var bool
     */
    protected bool $date = false;

    /**
     * @var bool
     */
    protected bool $dateAfterOrEqualToday = false;

    /**
     * @var string|null
     */
    protected ?string $dateEquals = null;

    /**
     * @var string|null
     */
    protected ?string $dateBefore = null;

    /**
     * @var string|null
     */
    protected ?string $dateBeforeOrEqual = null;

    /**
     * @var string|null
     */
    protected ?string $dateAfter = null;

    /**
     * @var string|null
     */
    protected ?string $dateAfterOrEqual = null;

    /**
     * @var string|null
     */
    protected ?string $dateFormat = null;

    /**
     * @var string|null
     */
    protected ?string $timezone = null;

    /**
     * @var array<string>|null
     */
    protected ?array $timezones = null;

    /**
     * @var array<string>
     */
    protected array $timezoneIdentifierCities = [];

    /**
     * @var int
     * No timezone
     */
    protected int $dateTimezoneGroupNumber = 0;

    /**
     * @var string
     */
    protected string $dateTimezoneGroupName = '';

    /**
     * @var bool
     */
    protected bool $uppercase = false;

    /**
     * @var bool
     */
    protected bool $lowerCase = false;

    /**
     * @var bool
     */
    protected bool $uppercaseFirstLetter = false;
    protected bool $lowercaseFirstLetter = false;

    protected bool $uppercaseWord = false;
    protected bool $lowerCaseWord = false;
    protected bool $onlyLettersAndSpaces = false;

    /**
     * @var string|null
     */
    protected ?string $regexPattern = null;

    /**
     * @var bool
     */
    protected bool $separateIntegersByComma = false;

    /**
     * @var bool
     */
    protected bool $separateStringsByComma = false;

    /**
     * @var bool
     */
    protected bool $separateStringsByUnderscore = false;

    /**
     * @var bool
     */
    protected bool $hexColor = false;

    /**
     * @var string|null
     */
    protected ?string $url = null;

    /**
     * @var bool
     * The field under validation must be a valid Universally Unique Lexicographically Sortable Identifier (ULID).
     */
    protected bool $ulid = false;

    /**
     * @var bool
     * The field under validation must be a valid RFC 4122 (version 1, 3, 4, or 5) universally unique identifier (UUID).
     */
    protected bool $uuid = false;

    /**
     * @var bool
     */
    protected bool $ip = false;

    /**
     * @var bool
     */
    protected bool $ipv4 = false;

    /**
     * @var bool
     */
    protected bool $ipv6 = false;

    /**
     * @var bool
     */
    protected bool $macAddress = false;

    /**
     * @var bool
     * The field under validation must be a valid JSON string.
     */
    protected bool $json = false;

    /**
     * @var string|null
     * The integer under validation must have a minimum length of value.
     */
    protected ?string $minDigits = null;

    /**
     * @var string|null
     * The integer under validation must have a maximum length of value.
     */
    protected ?string $maxDigits = null;

    /**
     * @var int|null
     */
    protected ?int $size = null;

    /**
     * @var string|null
     * The field under validation must be included in the given list of values in:foo,bar...
     */
    protected static ?string $in = null;

    /**
     * @var string|null
     * The field under validation must not be included in the given list of values not_in:foo,bar...
     */
    protected static ?string $notIn = null;

    /**
     * @var bool
     */
    protected bool $array = false;

    /**
     * @var bool
     */
    protected bool $arrayDistinct = false;

    /**
     * @var bool
     */
    protected ?bool $distinctStinct = false;

    /**
     * @var bool
     */
    protected bool $distinctIgnoreCase = false;

    /**
     * @return array<string>
     */
    protected function buildValidationRules(): array
    {
        /** @var array<string> $rules */
        $rules = [
            /**
             * --------------------------------------------------------------------------------
             * Default validations
             * --------------------------------------------------------------------------------
             */
            ...(static::$required === true ? [ShergelaRule::REQUIRED] : []),
            ...(static::$nullable === true ? [ShergelaRule::NULLABLE] : []),
            ...(static::$boolean === true ? [ShergelaRule::BOOLEAN] : []),
            ...(static::$in !== null ? [static::$in] : []),
            ...(static::$notIn !== null ? [static::$notIn] : []),
            ...($this->email === true ? [ShergelaRule::EMAIL] : []),
            ...($this->uniqueEmail !== null ? [$this->uniqueEmail] : []),
            ...($this->hexColor === true ? [ShergelaRule::HEX_COLOR] : []),
            ...($this->uuid === true ? [ShergelaRule::UUID] : []),
            ...($this->ulid === true ? [ShergelaRule::ULID] : []),

            /**
             * --------------------------------------------------------------------------------
             * IP Mac Address validation
             *--------------------------------------------------------------------------------
             */
            ...($this->ip === true ? [IPMACRule::IP] : []),
            ...($this->ipv4 === true ? [IPMACRule::IPV4] : []),
            ...($this->ipv6 === true ? [IPMACRule::IPV6] : []),
            ...($this->macAddress === true ? [IPMACRule::MAC_ADDRESS] : []),

            /**
             * --------------------------------------------------------------------------------
             * Numeric Validations
             *--------------------------------------------------------------------------------
             */
            ...($this->size !== null ? [IntegerRule::SIZE . $this->size] : []),
            ...($this->min !== null ? [IntegerRule::MIN . $this->min] : []),
            ...($this->max !== null ? [IntegerRule::MAX . $this->max] : []),
            ...($this->integer === true ? [IntegerRule::INTEGER] : []),
            ...($this->numeric === true ? [IntegerRule::NUMERIC] : []),
            ...($this->decimal !== null ? [$this->decimal] : []),
            ...($this->digits !== null ? [$this->digits] : []),
            ...($this->digitsBetween !== null ? [$this->digitsBetween] : []),
            ...($this->minDigits !== null ? [$this->minDigits] : []),
            ...($this->maxDigits !== null ? [$this->maxDigits] : []),

            /**
             * --------------------------------------------------------------------------------
             * String Validations
             *--------------------------------------------------------------------------------
             */
            ...($this->string === true ? [StringRule::STRING] : []),
            ...($this->alpha !== null ? [$this->alpha] : []),
            ...($this->alphaDash !== null ? [$this->alphaDash] : []),
            ...($this->alphaNum !== null ? [$this->alphaNum] : []),
            ...($this->uppercase === true ? [StringRule::UPPERCASE] : []),
            ...($this->lowerCase === true ? [StringRule::LOWERCASE] : []),
            ...($this->json === true ? [StringRule::JSON] : []),
            ...($this->url !== null ? [$this->url] : []),
            ...($this->startsWith !== null ? [$this->startsWith] : []),
            ...($this->endsWith !== null ? [$this->endsWith] : []),
            ...($this->doesntStartWith !== null ? [$this->doesntStartWith] : []),
            ...($this->doesntEndWith !== null ? [$this->doesntEndWith] : []),

            ...(
                $this->uppercaseFirstLetter === true
                    ? [new UppercaseFirstLetter(message: $this->validationMessage)]
                    : []
            ),

            ...(
                $this->lowercaseFirstLetter === true
                    ? [new LowercaseFirstLetter(message: $this->validationMessage)]
                    : []
            ),

            ...($this->uppercaseWord === true ? [new UppercaseWord(message: $this->validationMessage)] : []),
            ...($this->lowerCaseWord === true ? [new LowercaseWord(message: $this->validationMessage)] : []),

            ...(
                $this->onlyLettersAndSpaces === true
                    ? [new OnlyLettersAndSpaces(message: $this->validationMessage)]
                    : []
            ),

            /**
             * --------------------------------------------------------------------------------
             * Date Validations
             * --------------------------------------------------------------------------------
             */
            ...($this->date === true ? [DateRule::DATE] : []),
            ...($this->dateEquals !== null ? [DateRule::DATE_EQUALS . $this->dateEquals] : []),
            ...($this->dateBefore !== null ? [DateRule::DATE_BEFORE . $this->dateBefore] : []),
            ...($this->dateFormat !== null ? [DateRule::DATE_FORMAT . $this->dateFormat] : []),
            ...($this->timezone !== null ? [DateRule::TIMEZONE_ALL] : []),

            ...(
                $this->timezones !== null
                    ? [new TimezoneValidation(timezones: $this->timezones, message: $this->timezoneValidationMessage)]
                    : []
            ),

            ...(
                !empty($this->timezoneIdentifierCities)
                ? [
                    new TimezoneRegionValidation(
                        cities: $this->timezoneIdentifierCities,
                        timezoneGroupNumber: $this->dateTimezoneGroupNumber,
                        timezoneGroup: $this->dateTimezoneGroupName,
                        customMessage: $this->timezoneValidationMessage,
                    )
                ]
                : []
            ),

            ...($this->dateBeforeOrEqual !== null
                ? [DateRule::DATE_BEFORE_OR_EQUAL . $this->dateBeforeOrEqual]
                : []
            ),
            ...($this->dateAfterOrEqualToday === true ? [DateRule::DATE_AFTER_OR_EQUAL_TODAY] : []),
            ...($this->dateAfter !== null ? [DateRule::DATE_AFTER . $this->dateAfter] : []),
            ...($this->dateAfterOrEqual !== null
                ? [DateRule::DATE_AFTER_OR_EQUAL . $this->dateAfterOrEqual]
                : []
            ),

            /**
             * --------------------------------------------------------------------------------
             * Regex Validations
             *--------------------------------------------------------------------------------
             */
            ...($this->regexPattern !== null ? [RegexRule::RULE . $this->regexPattern] : []),

            ...(
                $this->separateIntegersByComma === true
                    ? [new IntegerByComma(message: $this->validationMessage)]
                    : []
            ),

            ...(
                $this->separateStringsByComma === true
                    ? [new StringByComma(message: $this->validationMessage)]
                    : []
            ),

            ...(
                $this->separateStringsByUnderscore === true
                    ? [new StringByUnderscore(message: $this->validationMessage)]
                    : []
            ),

            /**
             * --------------------------------------------------------------------------------
             * Array Validations
             *--------------------------------------------------------------------------------
             */
            ...($this->array === true ? [ArrayRule::ARRAY] : []),
            ...($this->arrayDistinct === true ? [ArrayRule::DISTINCT] : []),
            ...($this->distinctStinct === true ? [ArrayRule::DISTINCT_STRICT] : []),
            ...($this->distinctIgnoreCase === true ? [ArrayRule::DISTINCT_IGNORE_CASE] : []),
        ];

        return $rules;
    }
}
