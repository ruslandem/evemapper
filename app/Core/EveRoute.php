<?php

namespace App\Core;

use App\Core\Exceptions\EveRouteNotFoundException;
use Fisharebest\Algorithm\Dijkstra;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class EveRoute
{
    private const CACHE_PREFIX = 'EveRoute_';

    private ConnectionInterface $db;

    private bool $useCache;

    protected array $systemJumps = [];
    protected array $systemNames = [];


    public function __construct(string $connection = 'eve', bool $useCache = true)
    {
        $this->db = DB::connection($connection);
        $this->useCache = $useCache;
    }

    public function getWaypointsRoute(array $waypoints)
    {
        $destinations = $waypoints;
        $route = [
            array_shift($destinations)
        ];

        while (count($destinations) > 0) {
            $position = end($route);

            $paths = [];
            foreach ($destinations as $waypoint) {
                $waypointRoute = $this->getRoute($position, $waypoint);
                $paths[$waypoint] = count($waypointRoute);
            }

            if (count($paths) > 0) {
                $shortest = array_keys($paths, min($paths));
                $route[] = $shortest[0];

                if (($key = array_search($shortest[0], $destinations)) !== false) {
                    unset($destinations[$key]);
                }
            }
        }

        $result = [];
        if (count($route) > 1) {
            for ($i = 1; $i < count($route); $i++) {
                $result[] = $this->getRoute($route[$i - 1], $route[$i]);
            }
            $result[] = $this->getRoute($route[count($route) - 1], $route[0]);
        }

        return $result;
    }

    public function getRoute(string $fromSystemName, string $toSystemName)
    {
        if ($this->useCache) {
            $route = $this->getCachedRoute($fromSystemName, $toSystemName);
            if ($route) {
                return $route;
            }
        }

        $this->fillSystemArrays();

        $fromSolarSystemId = array_search($fromSystemName, $this->systemNames, true);
        $toSolarSystemId = array_search($toSystemName, $this->systemNames, true);

        if (!$fromSolarSystemId || !$toSolarSystemId) {
            throw new EveRouteNotFoundException();
        }

        $result = [];
        $algorithm = new Dijkstra($this->systemJumps);
        $route = $algorithm->shortestPaths($fromSolarSystemId, $toSolarSystemId);

        if (isset($route[0])) {
            $result = Utils::mapArray($route[0], $this->systemNames);
            if ($this->useCache) {
                $this->saveCachedRoute($fromSystemName, $toSystemName, $result);
            }
        }

        return $result;
    }

    protected function getCachedRoute(string $fromSystemName, string $toSystemName)
    {
        return Cache::get(
            self::getCacheKey($fromSystemName, $toSystemName)
        );
    }

    protected function saveCachedRoute(string $fromSystemName, string $toSystemName, array $route): bool
    {
        return Cache::put(
            self::getCacheKey($fromSystemName, $toSystemName),
            $route
        );
    }

    protected static function getCacheKey(string $fromSystemName, string $toSystemName): string
    {
        return implode('_', [
            self::CACHE_PREFIX,
            $fromSystemName,
            $toSystemName
        ]);
    }

    /**
     * Fill syatems data into arrays from database only in case
     * the arrays are empty.
     * 
     * @return void
     */
    protected function fillSystemArrays(): void
    {
        if (empty($this->systemJumps)) {
            $data = $this->db->table('mapSolarSystemJumps')->get();
            foreach ($data as $item) {
                $this->systemJumps[intval($item->fromSolarSystemID)][intval($item->toSolarSystemID)] = 1;
            }
        }

        if (empty($this->systemNames)) {
            $data = $this->db->table('mapSolarSystems')->get();
            foreach ($data as $item) {
                $this->systemNames[intval($item->solarSystemID)] = $item->solarSystemName;
            }
        }
    }

    // Query get XYZ-positions between gates

    // SELECT invItems.itemID as originID, invItems.locationID AS originLocationID, invNames.itemName, mapJumps.destinationID, destinationItems.locationID AS destinationLocationID,
    // originPositions.x as originX, originPositions.y as originY, originPositions.z as originZ,
    // destinationPositions.x as destinationX, destinationPositions.y as destinationY, destinationPositions.z as destinationZ
    // FROM invItems 
    // LEFT JOIN invNames ON invNames.itemID = invItems.itemID
    // LEFT JOIN invTypes ON invTypes.typeID = invItems.typeID
    // LEFT JOIN mapJumps ON mapJumps.stargateID = invItems.itemID
    // LEFT JOIN invItems AS destinationItems ON destinationItems.itemID = mapJumps.destinationID
    // LEFT JOIN invPositions AS originPositions ON originPositions.itemID = invNames.itemID
    // LEFT JOIN invPositions AS destinationPositions ON destinationPositions.itemID = mapJumps.destinationID
    // WHERE invItems.locationID = 30000140 AND invTypes.groupID = 10

}
