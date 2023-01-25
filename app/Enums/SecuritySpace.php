<?php

declare(strict_types=1);

namespace App\Enums;

enum SecuritySpace: int
{
    case Wormhole_C1 = 1;
    case Wormhole_C2 = 2;
    case Wormhole_C3 = 3;
    case Wormhole_C4 = 4;
    case Wormhole_C5 = 5;
    case Wormhole_C6 = 6;
    case High = 7;
    case Low = 8;
    case Null = 9;
    case Thera = 12;
    case Wormhole_13 = 13;
    case Drifters_Sentinel = 14;
    case Drifters_Barbican = 15;
    case Drifters_Vidette = 16;
    case Drifters_Conflux = 17;
    case Drifters_Redoubt = 18;
    case Triglavian = 99;

    public function name(): string
    {
        return match ($this) {
            SecuritySpace::Wormhole_C1 => 'C1',
            SecuritySpace::Wormhole_C2 => 'C2',
            SecuritySpace::Wormhole_C3 => 'C3',
            SecuritySpace::Wormhole_C4 => 'C4',
            SecuritySpace::Wormhole_C5 => 'C5',
            SecuritySpace::Wormhole_C6 => 'C6',
            SecuritySpace::High => 'High',
            SecuritySpace::Low => 'Low',
            SecuritySpace::Null => 'Null',
            SecuritySpace::Thera => 'Thera',
            SecuritySpace::Wormhole_13 => 'C13',
            SecuritySpace::Drifters_Sentinel => 'Drifters Sentinel',
            SecuritySpace::Drifters_Barbican => 'Drifters Barbican',
            SecuritySpace::Drifters_Vidette => 'Drifters Vidette',
            SecuritySpace::Drifters_Conflux => 'Drifters Conflux',
            SecuritySpace::Drifters_Redoubt => 'Drifters Redoubt',
            SecuritySpace::Triglavian => 'Triglavian',
        };
    }

    public function highlightColor(): string
    {
        return match ($this) {
            SecuritySpace::Wormhole_C1,
            SecuritySpace::Wormhole_C2,
            SecuritySpace::Wormhole_C3,
            SecuritySpace::Wormhole_C4,
            SecuritySpace::Wormhole_C5,
            SecuritySpace::Wormhole_C6,
            SecuritySpace::Wormhole_13 => '#9df2fb',
            SecuritySpace::High => '#8afb66',
            SecuritySpace::Low => '#f3ad2e',
            SecuritySpace::Null => '#f35757',
            SecuritySpace::Thera => '#df9fdf',
            default => '#dfdfdf',
        };
    }
}
