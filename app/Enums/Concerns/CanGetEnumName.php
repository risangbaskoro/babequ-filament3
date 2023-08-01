<?php

namespace App\Enums\Concerns;

trait CanGetEnumName
{
    /**
     * Get the name of the enum member from value.
     */
    public static function fromValue($value): int|string|array
    {
        foreach (self::cases() as $status) {
            if ($value === $status->value) {
                return $status->name;
            }
        }
        throw new \ValueError("$value is not a valid backing value for enum ".self::class);
    }
}
