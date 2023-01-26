<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Singleton;
use App\Models\SolarSystem;
use Illuminate\Support\Collection;

class SolarSystems extends Singleton
{
    public static function getAdjacentSystems(SolarSystem $solarSystem): Collection
    {
        return $solarSystem->jumps->pluck('toSolarSystem');
    }
}
