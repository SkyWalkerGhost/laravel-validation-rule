<?php

namespace Shergela\Validations\Constants;

class ValidationInteger
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

    /**
     * The field under validation must have a size matching the given value.
     * For string data, the value corresponds to the number of characters.
     * For numeric data, value corresponds to a given integer value
     * (the attribute must also have the numeric or integer rule).
     * For an array, size corresponds to the count of the array.
     * For files,
     * size corresponds to the file size in kilobytes.
     */
    public const SIZE = 'size:';
}
