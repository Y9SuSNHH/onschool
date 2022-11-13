<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class UserActiveEnum extends Enum
{
    public const INACTIVE = 0;
    public const ACTIVE   = 1;
}
