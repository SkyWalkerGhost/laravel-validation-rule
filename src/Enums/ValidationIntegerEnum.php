<?php

namespace Shergela\Validations\Enums;

enum ValidationIntegerEnum: string
{
    /**
     * Input value should be an integer
     */
    public const INTEGER = 'integer';

    /**
     * Input value should be an only numeric
     */
    public const NUMERIC = 'numeric';

    public const MIN = 'min:';
    public const MAX = 'max:';

    /**
     * The field under validation must be numeric and must contain the specified number of decimal places:
     */
    public const DECIMAL = 'decimal:';

    /**
     * The integer under validation must have an exact length of value.
    */
    public const DIGITS = 'digits:';

    /**
     * The integer validation must have a length between the given min and max.
     */
    public const DIGITS_BETWEEN = 'digits_between:';

    /**
     * The integer under validation must have a minimum length of value.
     */
    public const MIN_DIGITS = 'min_digits:';
    /**
     * The integer under validation must have a maximum length of value.
     */
    public const MAX_DIGITS = 'max_digits:';
}
