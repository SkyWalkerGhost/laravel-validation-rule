<?php

namespace Shergela\LaravelValidationRule\Enums;

enum ValidationRegexEnum: string
{
    /**
     * Regex rule
     */
    public const RULE = 'regex:';

    /**
     * Only letters
     */
    public const LETTERS = 'regex:/\pL/u';

    /**
     * Letters and spaces
     */
    public const LETTERS_AND_SPACES = 'regex:/^[\pL\s]+$/u';

    /**
     * Symbols
     */
    public const SYMBOLS = 'regex:/\p{Z}|\p{S}|\p{P}/u';

    /**
     * Numbers
     */
    public const NUMBERS = 'regex:/\pN/u';
}
