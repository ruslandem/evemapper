<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Exceptions\EveApiException;
use Illuminate\Support\Facades\DB;
use stdClass;

class EveSolarSystem extends DatabaseConnection
{
    public static function getById(int $solarSystemId): ?stdClass
    {
        $data = (object) self::db()->table('mapSolarSystems')
            ->join('mapConstellations', 'mapSolarSystems.constellationID', '=', 'mapConstellations.constellationID')
            ->join('mapRegions', 'mapSolarSystems.regionID', '=', 'mapRegions.regionID')
            ->where('mapSolarSystems.solarSystemID', '=', $solarSystemId)
            ->get()
            ->first();

        if (!property_exists($data, 'solarSystemName')) {
            throw new EveApiException("solar system ID {$solarSystemId} not found");
        }

        if (self::isWormholeSystem($data->solarSystemName)) {
            self::addWormholeSystemData($data);
        }

        self::addRatsInfo($data);
        self::addAdjacentSystems($data);

        return $data;
    }

    /**
     * @param string $solarSystemName
     * @throws EveApiException
     * @return stdClass|null
     */
    public static function getByName(string $solarSystemName): stdClass
    {
        $data = (object) self::db()->table('mapSolarSystems')
            ->join('mapConstellations', 'mapSolarSystems.constellationID', '=', 'mapConstellations.constellationID')
            ->join('mapRegions', 'mapSolarSystems.regionID', '=', 'mapRegions.regionID')
            ->where('mapSolarSystems.solarSystemName', '=', $solarSystemName)
            ->get()
            ->first();

        if (!property_exists($data, 'solarSystemName')) {
            throw new EveApiException("solar system name {$solarSystemName} not found");
        }

        if (self::isWormholeSystem($data->solarSystemName)) {
            self::addWormholeSystemData($data);
        }

        self::addRatsInfo($data);
        self::addAdjacentSystems($data);

        return $data;
    }

    public static function getByNames(array $names): array
    {
        return array_map('self::getByName', $names);
    }

    public static function search(string $searchText): array
    {
        $data = self::db()->table('mapSolarSystems')
            ->where('solarSystemName', 'like', $searchText . '%')
            ->get('solarSystemName');

        return $data->toArray();
    }

    public static function isWormholeSystem(string $solarSystemName): bool
    {
        return preg_match("/^J\d{6}$/", $solarSystemName, $matches) ||
            $solarSystemName == 'Thera';
    }

    public static function getStatistics(): array
    {
        $data = self::db()->table('signatures')
            ->select(
                'signatures.characterId',
                'users.characterName',
                DB::raw('COUNT(DISTINCT signatures.solarSystemName) as solarSystemsCount'),
                DB::raw('COUNT(DISTINCT signatures.signatureId) as signaturesCount'),
                DB::raw('MAX(signatures.updated_at) as updatedAt')
            )
            ->leftJoin('users', 'users.characterId', '=', 'signatures.characterId')
            ->groupBy(['signatures.characterId'])
            ->get();

        return $data->toArray();
    }

    protected static function addWormholeSystemData(stdClass &$data): void
    {
        $wormholeData = self::db()->table('wormholeSystems')
            ->where('id', '=', $data->solarSystemID)
            ->get()
            ->first();

        if (!$wormholeData) {
            // no data found
            return;
        }

        // fetching details for statics
        $statics = array_flip(
            explode(",", $wormholeData->static)
        );

        $wormholeData->static = [];
        foreach ($statics as $key => $value) {
            $details = self::db()->table('wormholeClasses')
                ->where('hole', '=', $key)
                ->get()
                ->first();

            if ($details) {
                $wormholeData->static[$key] = $details;
            }
        }

        // adding wormhole information to the solar system data
        $data->wormholeClass = 'C' . $wormholeData->class;
        $data->wormholePlanets = $wormholeData->planets;
        $data->wormholeMoons = $wormholeData->moons;
        $data->wormholeEffect = $wormholeData->effect;
        $data->wormholeStar = $wormholeData->star;
        $data->wormholeStatics = $wormholeData->static;
    }

    protected static function addRatsInfo(?stdClass &$data): void
    {
        if ($data instanceof stdClass) {
            if (self::isWormholeSystem($data->solarSystemName)) {
                $data->rats = 'Sleepers';
                return;
            }

            $rats = self::db()->table('regionRats')
                ->where('regionName', '=', $data->regionName)
                ->get()
                ->first();

            $data->rats = $rats ? $rats->rats : null;
        }
    }

    protected static function addAdjacentSystems(?stdClass &$data): void
    {
        if ($data instanceof stdClass) {
            $adjacentSystems = self::db()->table('mapSolarSystemJumps')
                ->where('fromSolarSystemID', '=', $data->solarSystemID)
                ->leftJoin('mapSolarSystems', 'mapSolarSystems.solarSystemID', '=', 'mapSolarSystemJumps.toSolarSystemID')
                ->leftJoin('mapConstellations', 'mapConstellations.constellationID', '=', 'mapSolarSystems.constellationID')
                ->leftJoin('mapRegions', 'mapRegions.regionID', '=', 'mapSolarSystems.regionID')
                ->leftJoin('regionRats', 'regionRats.regionName', '=', 'mapRegions.regionName')
                ->get([
                    'mapSolarSystems.solarSystemID',
                    'mapSolarSystems.solarSystemName',
                    'mapRegions.regionName',
                    'mapConstellations.constellationName',
                    'mapSolarSystems.security',
                    'regionRats.rats'
                ])
                ->toArray();

            $data->adjacentSystems = $adjacentSystems ? $adjacentSystems : [];
        }
    }
}
