<?php

namespace App\Enums;

enum Origin: string
{
    case Clever = 'Clever';
    case Hubject = 'Hubject';
    case OCPI = 'OCPI';

    public function label()
    {
        return match ($this) {
            self::Clever => 'Clever',
            self::Hubject => 'Hubject',
            self::OCPI => 'OCPI',
        };
    }

    public function circleColor()
    {
        return match ($this) {
            self::Clever => 'blue',
            self::Hubject => 'green',
            self::OCPI => 'red',
        };
    }
}
