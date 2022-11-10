<?php

namespace App\Core;

use Illuminate\Support\Facades\DB;
use stdClass;

class EveSolarSystem
{
    protected $data;

    public function __construct(int $solarSystemId, ?string $solarSystemName = null)
    {
        if ($solarSystemName) {
            $solarSystemId = $this->getSolarSystemByName($solarSystemName);
            if ($solarSystemId === null) {
                throw new \Exception('failed to find solar system');
            }
        }

        $this->data = $this->getSystemInfo($solarSystemId);

        if (
            $this->data &&
            preg_match("/^J\d{6}$/", $this->data->solarSystemName, $matches)
        ) {
            $this->getWormholeInfo();
        }
    }

    public function getData()
    {
        return $this->data;
    }

    protected function getSolarSystemByName(string $solarSystemName): ?int
    {
        $systems = DB::table('map_solar_systems')
            ->where('solarSystemName', '=', trim($solarSystemName))
            ->get();

        if ($systems->count() == 0) {
            // wormhole not found
            return null;
        }

        $found = $systems->first();

        if (isset($found->solarSystemID)) {
            return $found->solarSystemID;
        }

        return null;
    }

    protected function getWormholeInfo(): void
    {
        $solarSystemID = $this->data->solarSystemID;

        $wormholes = DB::table('wormhole_systems')
            ->where('id', '=', $solarSystemID)
            ->get();

        if ($wormholes->count() == 0) {
            // wormhole not found
            return;
        }

        $found = $wormholes->first();

        $statics = array_flip(
            explode(",", $found->static)
        );

        $found->static = [];
        foreach ($statics as $staticName => $data) {
            $classes = DB::table('wormhole_classes')
                ->where('hole', '=', $staticName)
                ->get();

            if (count($classes) > 0) {
                $found->static[$staticName] = $classes->first();
            }
        }

        // adding wormhole information to the solar system data
        $this->data->wormholeClass = 'C' . $found->class;
        $this->data->wormholePlanets = $found->planets;
        $this->data->wormholeMoons = $found->moons;
        $this->data->wormholeEffect = $found->effect;
        $this->data->wormholeStar = $found->star;
        $this->data->wormholeStatics = $found->static;
    }

    protected function getSystemInfo(int $solarSystemId): ?stdClass
    {
        $systems = DB::table('map_solar_systems')
            ->join('map_constellations', 'map_solar_systems.constellationID', '=', 'map_constellations.constellationID')
            ->join('map_regions', 'map_solar_systems.regionID', '=', 'map_regions.regionID')
            ->where('solarSystemId', '=', $solarSystemId)
            ->get();

        if ($systems->count() == 0) {
            // wormhole not found
            return null;
        }

        return $systems->first();
    }
}

// {#311 ▼
//     +"regionID": 10000002
//     +"constellationID": 20000019
//     +"solarSystemID": 30000136
//     +"solarSystemName": "Ohmahailen"
//     +"x": -1.497941536651E+17
//     +"y": 4.2906489131106E+16
//     +"z": 1.04942953562E+17
//     +"xMin": -1.4979537489843E+17
//     +"xMax": -1.4978949980307E+17
//     +"yMin": 4.2906340562747E+16
//     +"yMax": 4.2906528340339E+16
//     +"zMin": -1.0494619631418E+17
//     +"zMax": -1.049423036037E+17
//     +"luminosity": 0.02571
//     +"border": 0
//     +"fringe": 0
//     +"corridor": 0
//     +"hub": 1
//     +"international": 0
//     +"regional": 0
//     +"constellation": null
//     +"security": 0.66381082288412
//     +"factionID": 500001
//     +"radius": 2937547682528.0
//     +"sunTypeID": 3800
//     +"securityClass": "C"
//   }
