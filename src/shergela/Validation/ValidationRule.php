<?php

namespace Shergela\LaravelValidationRule\Validation;

use Shergela\LaravelValidationRule\Enums\IPMACAddressValidationEnum as IPMACRule;
use Shergela\LaravelValidationRule\Enums\ValidationDateEnum as DateRule;
use Shergela\LaravelValidationRule\Enums\ValidationIntegerEnum as IntegerRule;
use Shergela\LaravelValidationRule\Enums\ValidationRegexEnum as RegexRule;
use Shergela\LaravelValidationRule\Enums\ValidationRuleEnum as Rule;
use Shergela\LaravelValidationRule\Enums\ValidationStringEnum as StringRule;

class ValidationRule
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
     * @var bool
     */
    protected bool $upperCase = false;

    /**
     * @var bool
     */
    protected bool $lowerCase = false;

    /**
     * @var string|null
     */
    protected ?string $regexPattern = null;

    /**
     * @var bool
     */
    protected bool $hexColor = false;

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
     * @return array<int, string>
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
            ...($this->email === true ? [Rule::EMAIL] : []),
            ...($this->uniqueEmail !== null ? [$this->uniqueEmail] : []),
            ...($this->hexColor === true ? [Rule::HEX_COLOR] : []),

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
            ...($this->upperCase === true ? [StringRule::UPPERCASE] : []),
            ...($this->lowerCase === true ? [StringRule::LOWERCASE] : []),
            ...($this->json === true ? [StringRule::JSON] : []),

            /**
             * --------------------------------------------------------------------------------
             * Date Validations
             * --------------------------------------------------------------------------------
             */
            ...($this->date === true ? [DateRule::DATE] : []),
            ...($this->dateEquals !== null ? [DateRule::DATE_EQUALS . $this->dateEquals] : []),
            ...($this->dateBefore !== null ? [DateRule::DATE_BEFORE . $this->dateBefore] : []),
            ...($this->dateFormat !== null ? [DateRule::DATE_FORMAT . $this->dateFormat] : []),
            ...($this->timezone !== null ? [DateRule::TIMEZONE . $this->timezone] : []),
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
        ];
    }
}
