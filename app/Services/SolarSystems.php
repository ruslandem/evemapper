<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Singleton;
use App\Models\SolarSystem;
use Illuminate\Support\Collection;

class SolarSystems extends Singleton
{
    public static function getById(int $solarSystemId): ?SolarSystem
    {
        return SolarSystem::where(['solarSystemID' => $solarSystemId])
            ->with(['region', 'constellation', 'wormhole', 'jumps'])
            ->first();
    }

    public static function getByName(string $solarSystemName): ?SolarSystem
    {
        return SolarSystem::where(['solarSystemName' => $solarSystemName])
            ->with(['region', 'constellation', 'wormhole', 'jumps'])
            ->first();
    }

    public static function getAdjacentSystems(SolarSystem $solarSystem): Collection
    {
        return $solarSystem->jumps->pluck('toSolarSystem');
    }

    public static function filterByName(string $searchText): array
    {
        $data = SolarSystem::where('solarSystemName', 'like', $searchText . '%')
            ->get()
            ->map(
                function (SolarSystem $item) {
                    return $item->solarSystemName;
                }
            );

        return $data->toArray();
    }
}
