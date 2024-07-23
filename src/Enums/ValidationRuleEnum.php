<?php

namespace Shergela\Validations\Enums;

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

    /**
     * The field under validation must be a valid RFC 4122 (version 1, 3, 4, or 5) universally unique identifier (UUID).
     */
    public const UUID = 'uuid';

    /**
     * The field under validation must be a valid Universally Unique Lexicographically Sortable Identifier (ULID).
     */
    public const ULID = 'ulid';

    /**
     * The field under validation must be included in the given list of values in:foo,bar...
     */
    public const IN = 'in:';

    /**
     * The field under validation must not be included in the given list of values not_in:foo,bar,bar...
     */
    public const NOT_IN = 'not_in:';
}
