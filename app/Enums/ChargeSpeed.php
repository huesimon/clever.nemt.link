<?php

namespace App\Enums;

enum ChargeSpeed: string
{
    case slow = 'slow';
    case fast = 'fast';
    case hyper = 'hyper';

    public function label()
    {
        return match ($this) {
            self::slow => 'Slow',
            self::fast => 'Fast',
            self::hyper => 'Hyper',
        };
    }

    public function kwhRange(): array
    {
        return match ($this) {
            self::slow => [0, 35],
            self::fast => [35, 100],
            self::hyper => [100, 1000],
        };
    }
}
