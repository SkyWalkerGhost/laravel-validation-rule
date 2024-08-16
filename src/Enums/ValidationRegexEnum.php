<?php

namespace Shergela\Validations\Enums;

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

    /**
     * Separate integers by comma (1,2,3,4,5).
     */
    public const SEPARATE_INTEGERS_BY_COMMA = "/^\d+(?:,\d+)*$/";

    /**
     * Separate strings by comma. (ana,gustav,john).
     */
    public const SEPARATE_STRINGS_BY_COMMA = "/^[a-z]+(?:,[a-z]+)*$/";

    /**
     * Separate strings by underscore (ana_gustav_john).
     */
    public const SEPARATE_STRINGS_BY_UNDERSCORE = "/^[a-z]+(?:_[a-z]+)*$/";
}
