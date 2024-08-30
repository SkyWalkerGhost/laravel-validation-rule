<?php

namespace Shergela\Validations\Enums;

enum ValidationArrayEnum: string
{
    /**
     * Input value must be a date.
     * Date validations
     */
    public const ARRAY = 'array';
    public const DISTINCT = 'distinct';
    public const DISTINCT_STRICT = 'distinct:strict';
    public const DISTINCT_IGNORE_CASE = 'distinct:ignore_case';
}
