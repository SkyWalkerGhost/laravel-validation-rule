<?php

namespace Shergela\Validations\Validation;

use Shergela\Validations\Enums\IPMacValidationEnum as IPMACRule;
use Shergela\Validations\Enums\ValidationDateEnum as DateRule;
use Shergela\Validations\Enums\ValidationIntegerEnum as IntegerRule;
use Shergela\Validations\Enums\ValidationRegexEnum as RegexRule;
use Shergela\Validations\Enums\ValidationRuleEnum as Rule;
use Shergela\Validations\Enums\ValidationStringEnum as StringRule;
use Shergela\Validations\Rules\LowercaseFirstLetter;
use Shergela\Validations\Rules\SeparateIntegersByComma;
use Shergela\Validations\Rules\SeparateStringsByComma;
use Shergela\Validations\Rules\SeparateStringsByUnderscore;
use Shergela\Validations\Rules\TimezoneValidation;
use Shergela\Validations\Rules\TimezoneRegionValidation;
use Shergela\Validations\Rules\UppercaseFirstLetter;

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
     * @return array<UppercaseFirstLetter|SeparateIntegersByComma|SeparateStringsByComma|SeparateStringsByUnderscore|string>
     */
    protected function buildValidationRules(): array
    {
        return [
            /**
             * --------------------------------------------------------------------------------
             * Default validations
             * --------------------------------------------------------------------------------
             */
            ...(static::$required === true ? [Rule::REQUIRED] : []),
            ...(static::$nullable === true ? [Rule::NULLABLE] : []),
            ...(static::$boolean === true ? [Rule::BOOLEAN] : []),
            ...(static::$in !== null ? [static::$in] : []),
            ...(static::$notIn !== null ? [static::$notIn] : []),
            ...($this->email === true ? [Rule::EMAIL] : []),
            ...($this->uniqueEmail !== null ? [$this->uniqueEmail] : []),
            ...($this->hexColor === true ? [Rule::HEX_COLOR] : []),
            ...($this->uuid === true ? [Rule::UUID] : []),
            ...($this->ulid === true ? [Rule::ULID] : []),

            /**
             * IP | MAC address validations
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
            ...($this->uppercaseFirstLetter === true ? [new UppercaseFirstLetter()] : []),
            ...($this->lowercaseFirstLetter === true ? [new LowercaseFirstLetter()] : []),

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
            ...($this->timezones !== null ? [new TimezoneValidation(timezones: $this->timezones)] : []),

            ...(
                !empty($this->timezoneIdentifierCities)
                ? [
                    new TimezoneRegionValidation(
                        cities: $this->timezoneIdentifierCities,
                        timezoneGroupNumber: $this->dateTimezoneGroupNumber,
                        timezoneGroup: $this->dateTimezoneGroupName
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
             * Regex validations
             */
            ...($this->regexPattern !== null ? [RegexRule::RULE . $this->regexPattern] : []),
            ...($this->separateIntegersByComma === true ? [new SeparateIntegersByComma()] : []),
            ...($this->separateStringsByComma === true ? [new SeparateStringsByComma()] : []),
            ...($this->separateStringsByUnderscore === true ? [new SeparateStringsByUnderscore()] : []),
        ];
    }
}
