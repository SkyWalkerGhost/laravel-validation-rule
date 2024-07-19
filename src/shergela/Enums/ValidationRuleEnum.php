<?php

namespace Shergela\LaravelValidationRule\Enums;

enum ValidationRuleEnum: string
{
    /**
     * Value is required
     */
    public const REQUIRED = 'required';

    /**
     * Value is not present
     */
    public const NULLABLE = 'nullable';

    /**
     * The field under validation must be able to be cast as a boolean.
     * Accepted input are true, false, 1, 0, "1", and "0".
     */
    public const BOOLEAN = 'boolean';

    /**
     * Input value should be an email
     */
    public const EMAIL = 'email';
    public const UNIQUE_EMAIL = 'unique:';

    /**
     * The field under validation must contain a valid color value in hexadecimal format.
     */
    public const HEX_COLOR = 'hex_color';
}