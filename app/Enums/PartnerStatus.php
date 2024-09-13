<?php

namespace App\Enums;

enum PartnerStatus: string
{
    case None = 'None';
    case BYH = 'BYH';
    case EWII = 'EWII';
    case PoweredBy = 'POWEREDBY';

    public function label()
    {
        return match ($this) {
            self::None => 'None',
            self::BYH => 'By & Havn',
            self::EWII => 'EWII',
            self::PoweredBy => 'Powered by Clever & E.ON',
        };
    }

    public function isIncluded()
    {
        return match ($this) {
            self::None => false,
            self::BYH => true,
        };
    }
}
