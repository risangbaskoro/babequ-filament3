<?php

namespace App\Enums\Concerns;

trait Arrayable
{
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function toArray(): array
    {
        return array_combine(self::values(), self::names());
    }
}
