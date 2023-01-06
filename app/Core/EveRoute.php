<?php

namespace App\Core;

use App\Core\Exceptions\EveRouteNotFoundException;
use Fisharebest\Algorithm\Dijkstra;
use Illuminate\Support\Facades\Cache;

class EveRoute extends DatabaseConnection
{
    public static bool $useCache = true;

    protected static array $mapSystems = [];
    protected static array $mapJumps = [];

    /**
     * Get shortest route through several waypoints with the first waypoint 
     * as origin and the last waypoint as destination.
     * 
     * @param array $waypoints
     * @return array
     */
    public static function getWaypointsRoute(array $waypoints): array
    {
        $destinations = $waypoints;
        $route = [
            array_shift($destinations)
        ];

        while (count($destinations) > 0) {
            $position = end($route);

            $paths = [];
            foreach ($destinations as $waypoint) {
                $waypointRoute = self::getRoute($position, $waypoint);
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
                $result[] = self::getRoute($route[$i - 1], $route[$i]);
            }
            $result[] = self::getRoute($route[count($route) - 1], $route[0]);
        }

        return $result;
    }

    /**
     * Get shortest route between two solar systems.
     * 
     * @param string $originName
     * @param string $destName
     * @return array
     */
    public static function getRoute(string $originName, string $destName): array
    {
        $route = self::getCachedRoute($originName, $destName);

        if ($route !== null) {
            return $route;
        }

        if (sizeof(self::$mapSystems) === 0) {
            self::$mapSystems = self::getSolarSystemsMap();
        }

        if (sizeof(self::$mapJumps) === 0) {
            self::$mapJumps = self::getSolarSystemJumpsMap();
        }

        $originId = array_search($originName, self::$mapSystems, true);
        $destId = array_search($destName, self::$mapSystems, true);

        if (!$originId || !$destId) {
            throw new EveRouteNotFoundException();
        }

        $algorithm = new Dijkstra(self::$mapJumps);
        $route = $algorithm->shortestPaths($originId, $destId);

        $result = [];

        if (isset($route[0])) {
            $result = Utils::mapArray($route[0], self::$mapSystems);
            if (self::$useCache) {
                self::saveCachedRoute($originName, $destName, $result);
            }
        }

        return $result;
    }

    protected static function getCachedRoute(string $originName, string $destName): ?array
    {
        if (static::$useCache) {
            return (array) Cache::get(
                self::getCacheKey($originName, $destName)
            );
        }

        return null;
    }

    protected static function saveCachedRoute(string $originName, string $destName, array $route): bool
    {
        return Cache::put(
            self::getCacheKey($originName, $destName),
            $route
        );
    }

    protected static function getCacheKey(string $originName, string $destName): string
    {
        $className = (new \ReflectionClass(EveRoute::class))->getShortName();
        return implode('_', [$className, $originName, $destName]);
    }


    /**
     * Get map of solar system names by id.
     * 
     * @return array
     */
    protected static function getSolarSystemsMap(): array
    {
        $data = self::db()->table('mapSolarSystems')->get();

        $result = [];
        foreach ($data as $item) {
            $id = intval($item->solarSystemID);
            $result[$id] = $item->solarSystemName;
        }

        return $result;
    }

    /**
     * Get map of solar system jumps to use in Dijkstra algorithm.
     * 
     * @see https://en.wikipedia.org/wiki/Dijkstra%27s_algorithm
     * @return array
     */
    protected static function getSolarSystemJumpsMap(): array
    {
        $data = self::db()->table('mapSolarSystemJumps')->get();

        $result = [];
        foreach ($data as $item) {
            $from = intval($item->fromSolarSystemID);
            $to = intval($item->toSolarSystemID);
            $result[$from][$to] = 1;
        }

        return $result;
    }
}
