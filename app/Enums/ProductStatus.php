<?php

namespace App\Enums;

use App\Enums\Concerns\Arrayable;
use App\Enums\Concerns\CanGetEnumName;

enum ProductStatus: int
{
    use Arrayable;
    use CanGetEnumName;

    case PENDING = 1;
    case APPROVED = 2;
    case REJECTED = 3;
}
