<?php

namespace Shergela\Validations\Constants;

class ValidationDate
{
    /**
     * Input value must be a date.
     * Date validations
     */
    public const DATE = 'date';
    public const DATE_BEFORE = 'before:';
    public const DATE_BEFORE_OR_EQUAL = 'before_or_equal:';
    public const DATE_AFTER = 'after:';
    public const DATE_AFTER_OR_EQUAL_TODAY = 'after_or_equal:today';
    public const DATE_AFTER_OR_EQUAL = 'after_or_equal:';
    public const DATE_EQUALS = 'date_equals:';

    public const DATE_FORMAT = 'date_format:';
    public const TIMEZONE = 'timezone';
    public const TIMEZONE_ALL = 'timezone:all';
}
