<?php

namespace App\Core;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;
use stdClass;

class EveSolarSystem
{
    private ConnectionInterface $db;

    public function __construct(string $db = 'app')
    {
        $this->db = DB::connection($db);
    }

    public function getById(int $solarSystemId): ?stdClass
    {
        $data = $this->db->table('mapSolarSystems')
            ->join('mapConstellations', 'mapSolarSystems.constellationID', '=', 'mapConstellations.constellationID')
            ->join('mapRegions', 'mapSolarSystems.regionID', '=', 'mapRegions.regionID')
            ->where('mapSolarSystems.solarSystemID', '=', $solarSystemId)
            ->get()
            ->first();

        if ($data && self::isWormholeSystem($data->solarSystemName)) {
            $this->addWormholeSystemData($data);
        }

        $this->addRatsInfo($data);

        return $data;
    }

    public function getByName(string $solarSystemName): ?stdClass
    {
        $data = $this->db->table('mapSolarSystems')
            ->join('mapConstellations', 'mapSolarSystems.constellationID', '=', 'mapConstellations.constellationID')
            ->join('mapRegions', 'mapSolarSystems.regionID', '=', 'mapRegions.regionID')
            ->where('mapSolarSystems.solarSystemName', '=', $solarSystemName)
            ->get()
            ->first();

        if ($data && self::isWormholeSystem($data->solarSystemName)) {
            $this->addWormholeSystemData($data);
        }

        $this->addRatsInfo($data);

        return $data;
    }

    public function getByNames(array $names): array
    {
        return array_map([$this, 'getByName'], $names);
    }

    public function search(string $searchText): array
    {
        $data = $this->db->table('mapSolarSystems')
            ->where('solarSystemName', 'like', $searchText . '%')
            ->get('solarSystemName');

        return $data->toArray();
    }

    public static function isWormholeSystem(string $solarSystemName): bool
    {
        return preg_match("/^J\d{6}$/", $solarSystemName, $matches) ||
            $solarSystemName == 'Thera';
    }

    public function getStatistics(): array
    {
        $data = $this->db->table('signatures')
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

    protected function addWormholeSystemData(stdClass &$data): void
    {
        $wormholeData = $this->db->table('wormholeSystems')
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
            $details = $this->db->table('wormholeClasses')
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

    protected function addRatsInfo(?stdClass &$data): void
    {
        if ($data instanceof stdClass) {

            if (self::isWormholeSystem($data->solarSystemName)) {
                $data->rats = 'Sleepers';
                return;
            }

            $rats = $this->db->table('regionRats')
                ->where('regionName', '=', $data->regionName)
                ->get()
                ->first();

            $data->rats = $rats ? $rats->rats : null;
        }
    }
}
