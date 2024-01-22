<?php

namespace App\Enums;

enum PartnerStatus: string
{
    case None = 'None';
    case BYH = 'BYH';

    public function label()
    {
        return match ($this) {
            self::None => 'None',
            self::BYH => 'By & Havn',
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
