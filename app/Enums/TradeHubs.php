<?php

declare(strict_types=1);

namespace App\Enums;

enum TradeHubs: int
{
    case Jita = 30000142;
    case Amarr = 30002187;
    case Dodixie = 30002659;
    case Rens = 30002510;
    case Hek = 30002053;
}
