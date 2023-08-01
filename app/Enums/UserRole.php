<?php

namespace App\Enums;

use App\Enums\Concerns\Arrayable;
use App\Enums\Concerns\CanGetEnumName;

enum UserRole: int
{
    use Arrayable;
    use CanGetEnumName;

    case ADMIN = 1;
    case CITIZEN = 2;
    case VISITOR = 3;
}
