<?php

namespace Shergela\Validations\Enums;

enum ValidationStringEnum: string
{
    /**
     * Input value should be a string
     */
    public const STRING = 'string';
    public const UPPERCASE = 'uppercase';
    public const LOWERCASE = 'lowercase';

    /**
     * The field under validation must be entirely Unicode alphabetic characters contained in \p{L} and \p{M}.
     * To restrict this validation rule to characters in the ASCII range (a-z and A-Z),
     * you may provide the ascii option to the validation rule:
     */
    public const ALPHA = 'alpha';

    /**
     * The field under validation must be entirely Unicode alphanumeric characters contained in \p{L}, \p{M}, \p{N},
     * as well as ASCII dashes (-) and ASCII underscores (_).
     * To restrict this validation rule to characters in the ASCII range (a-z and A-Z),
     * you may provide the ascii option to the validation rule:
     */
    public const ALPHA_DASH = 'alpha_dash';

    /**
     * The field under validation must be entirely Unicode alphanumeric characters
     * contained in \p{L}, \p{M}, and \p{N}.
     * To restrict this validation rule to characters in the ASCII range (a-z and A-Z),
     * you may provide the ascii option to the validation rule:
     */
    public const ALPHA_NUM = 'alpha_num';

    /**
     * The field under validation must be a valid JSON string.
    */
    public const JSON = 'json';

    /**
     * The field under validation must be a valid URL.
     */
    public const URL = 'url';
}
