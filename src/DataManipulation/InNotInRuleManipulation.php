<?php

namespace Shergela\Validations\DataManipulation;

use BackedEnum;
use Illuminate\Contracts\Support\Arrayable;
use UnitEnum;

class InNotInRuleManipulation
{
    /**
     * @param Arrayable<int, string>|array<string>|string $values
     */
    public function __construct(
        protected readonly Arrayable|array|string $values,
        protected readonly bool $in = false,
        protected readonly bool $notIn = false,
    ) {
    }

    /**
     * @return string
     */
    private function manipulation(): string
    {
        $values = $this->values;
        $rule = $this->in === true ? 'in:' : 'not_in:';

        $values = array_map(function ($value) {
            $value = match (true) {
                 $value instanceof BackedEnum => $value->value,
                 $value instanceof UnitEnum => $value->name,
                default => $value,
            };

            /** @var string $v */
            $v = $value;

            return '"' . str_replace('"', '""', $v) . '"';
        }, (array) $values);

        return $rule . implode(',', $values);
    }

    /**
     * @return string
     */
    public function handle(): string
    {
        return $this->manipulation();
    }
}
