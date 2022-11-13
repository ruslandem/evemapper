<?php

namespace App\Core;

use Illuminate\Database\ConnectionInterface;

class EveRoute
{
    protected $db;

    protected $regionJumps;
    protected $constellationJumps;
    protected $systemJumps;
    protected $solarSystemNames;

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;

        $this->regionJumps = [];
        $data = $this->db->table('mapRegionJumps')->get();
        foreach ($data as $item) {
            $this->regionJumps[intval($item->fromRegionID)][] = intval($item->toRegionID);
        }

        $this->constellationJumps = [];
        $data = $this->db->table('mapConstellationJumps')->get();
        foreach ($data as $item) {
            $this->constellationJumps[intval($item->fromConstellationID)][] = intval($item->toConstellationID);
        }

        $this->systemJumps = [];
        $data = $this->db->table('mapSolarSystemJumps')->get();
        foreach ($data as $item) {
            $this->systemJumps[intval($item->fromSolarSystemID)][] = intval($item->toSolarSystemID);
        }

        $this->solarSystemNames = [];
        $data = $this->db->table('mapSolarSystems')->get();
        foreach ($data as $item) {
            $this->solarSystemNames[intval($item->solarSystemID)] = $item->solarSystemName;
        }
    }

    public function getRoute(string $fromSolarSystemName, string $toSolarSystemName)
    {
        $from = $this->db->table('mapSolarSystems')
            ->where('solarSystemName', '=', $fromSolarSystemName)
            ->first();

        $to = $this->db->table('mapSolarSystems')
            ->where('solarSystemName', '=', $toSolarSystemName)
            ->first();

        if (!$from || !$to) {
            throw new \Exception('origin or target system not found');
        }

        $route = $this->findRoute($this->systemJumps, $from->solarSystemID, $to->solarSystemID);

        // debug output
        $names = [];
        foreach ($route as $id) {
            $names[] = $id . ':' . $this->solarSystemNames[$id];
        }
        dd($names);
    }

    protected function getRegionsRoute(int $fromRegionId, int $toRegionId)
    {
        # code...
    }

    protected function findRoute(array $map, int $from, int $to, int $maxRoute = 15)
    {
        $failedRoutes = [];

        do {
            $route = [$from];
            do {
                $count = count($route);
                $waypoints = $map[$route[$count - 1]];
                if (count($waypoints) > 0) {
                    $gotNewWaypoint = false;
                    foreach ($waypoints as $waypoint) {
                        if (!in_array($waypoint, $route)) {
                            $route[] = $waypoint;

                            if (in_array($route, $failedRoutes)) {
                                // failed route
                                array_pop($route);
                                continue;
                            }

                            $gotNewWaypoint = true;
                            break;
                        }
                    }

                    if (count($route) > $maxRoute) {
                        // waypoints limit reached
                        $failedRoutes[] = $route;
                        break;
                    }

                    if (!$gotNewWaypoint) {
                        // no more waypoints available
                        break;
                    }
                }
            } while (end($route) != $to);
            if (end($route) != $to) {
                $failedRoutes[] = $route;
            }
        } while (end($route) != $to && count($failedRoutes) < 1000);

        return $route;
    }

    // protected function getSampleRoute(int $fromSolarSystemId, int $toSolarSystemId, array &$failedRoutes)
    // {
    //     $route = [$fromSolarSystemId];

    //     do {
    //         $count = count($route);
    //         $waypoints = $this->jumps[$route[$count - 1]];

    //         if ($waypoints > 1) {
    //             $routeSize = count($route);
    //             foreach ($waypoints as $waypoint) {
    //                 if (!in_array($waypoint, $route)) {
    //                     $route[] = $waypoint;
    //                     if (in_array($route, $failedRoutes)) {
    //                         array_pop($route);
    //                         continue;
    //                     }
    //                     break;
    //                 }
    //             }
    //             if ($routeSize > 12) {
    //                 // too much waypoints
    //                 break;
    //             }
    //             if ($routeSize == count($route)) {
    //                 // no more waypoints
    //                 break;
    //             }
    //             if (end($route) == $toSolarSystemId) {
    //                 // destination reached
    //                 break;
    //             }
    //         }
    //     } while (count($waypoints) > 1);

    //     return $route;
    // }
}
