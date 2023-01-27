<?php

namespace App\Services;

use App\Core\Exceptions\EveRouteNotFoundException;
use App\Core\Singleton;
use Fisharebest\Algorithm\Dijkstra;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final class SolarSystemRoutes extends Singleton
{
    /**
     * Trigger to use cache to drastically speed up data processing.
     * (not recommended to switch off in production)
     * 
     * @var bool by default TRUE
     */
    public static bool $useCache = true;

    /**
     * Map solar system names by ID (used in Dijkstra algorithm)
     * 
     * @var array
     */
    protected static array $mapSystems = [];

    /**
     * Map jumps between two solar systems (used in Dijkstra algorithm)
     * 
     * @var array
     */
    protected static array $mapJumps = [];

    /**
     * Get shortest route through several waypoints with the first waypoint 
     * as origin and the last waypoint as destination.
     * 
     * @param array $waypoints
     * @return array
     */
    public static function getWaypointsRoute(array $waypoints, bool $detailed = false): array
    {
        $route = self::getReorderedRouteWaypoints($waypoints);

        if (count($route) > 1) {
            $result = [];
            for ($i = 1; $i < count($route); $i++) {
                $result[] = self::getRoute($route[$i - 1], $route[$i]);
            }
            $result[] = self::getRoute($route[count($route) - 1], $route[0]);
        }

        if ($detailed && $result) {
            return self::fetchRouteSystemsDetails($result);
        }

        return $result ?? [];
    }

    /**
     * Get detailed waypoints route
     * 
     * @param array $route
     * @return array
     */
    protected static function fetchRouteSystemsDetails(array $route): array
    {
        $result = [];

        foreach ($route as $waypointRouteIndex => $waypointRoute) {
            foreach ($waypointRoute as $waypointIndex => $waypoint) {
                $info = SolarSystems::getByName($waypoint);

                $result[$waypointRouteIndex][$waypointIndex] = [
                    'solarSystemID' => $info->solarSystemID,
                    'solarSystemName' => $info->solarSystemName,
                    'constellationName' => $info->constellation->constellationName,
                    'regionName' => $info->region->regionName,
                    'security' => $info->security,
                    'rats' => $info->region->rats->rats,
                ];
            }
        }

        return $result;
    }

    /**
     * Reorder intermediate waypoints to get shortest possible route
     * 
     * @param array $waypoints
     * @return array
     */
    protected static function getReorderedRouteWaypoints(array $waypoints): array
    {
        $destinations = $waypoints;

        // get first route waypoint as origin
        $route = [
            array_shift($waypoints)
        ];

        // get next route waypoints by selecting the nearest one
        while (count($destinations) > 0) {
            $position = end($route);

            $paths = [];
            foreach ($destinations as $waypoint) {
                $paths[$waypoint] = self::getRouteLength($position, $waypoint);
            }

            if (count($paths) > 0) {
                $shortest = array_keys($paths, min($paths));
                $route[] = $shortest[0];

                if (($key = array_search($shortest[0], $destinations)) !== false) {
                    unset($destinations[$key]);
                }
            }
        }

        return $route;
    }

    /**
     * Get minimal amount of jumps between two solar systems
     * 
     * @param string $originName
     * @param string $destName
     * @return int
     */
    public static function getRouteLength(string $originName, string $destName): int
    {
        return count(
            self::getRoute($originName, $destName)
        );
    }

    /**
     * Get shortest route between two solar systems
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

        // fill maps from the database if they are empty
        self::initMaps();

        $originId = array_search($originName, self::$mapSystems, true);
        $destId = array_search($destName, self::$mapSystems, true);

        if (!$originId || !$destId) {
            throw new EveRouteNotFoundException();
        }

        $algorithm = new Dijkstra(self::$mapJumps);
        $route = $algorithm->shortestPaths($originId, $destId);

        if (isset($route[0])) {
            $result = Utils::mapArray($route[0], self::$mapSystems);
            if (self::$useCache) {
                self::saveCachedRoute($originName, $destName, $result);
            }
        }

        return $result ?? [];
    }

    /**
     * Data maps methods
     */

    protected static function initMaps(): void
    {
        if (sizeof(self::$mapSystems) === 0) {
            self::$mapSystems = self::getSolarSystemsMap();
        }

        if (sizeof(self::$mapJumps) === 0) {
            self::$mapJumps = self::getSolarSystemJumpsMap();
        }
    }

    /**
     * Get map of solar system names by id using map (use in Dijkstra algorithm).
     * 
     * @return array
     */
    protected static function getSolarSystemsMap(): array
    {
        $data = DB::table('mapSolarSystems')->get();

        $result = [];
        foreach ($data as $item) {
            $id = intval($item->solarSystemID);
            $result[$id] = $item->solarSystemName;
        }

        return $result;
    }

    /**
     * Get map of solar system jumps to (use in Dijkstra algorithm).
     * 
     * @see https://en.wikipedia.org/wiki/Dijkstra%27s_algorithm
     * @return array
     */
    protected static function getSolarSystemJumpsMap(): array
    {
        $data = DB::table('mapSolarSystemJumps')->get();

        $result = [];
        foreach ($data as $item) {
            $from = intval($item->fromSolarSystemID);
            $to = intval($item->toSolarSystemID);
            $result[$from][$to] = 1;
        }

        return $result;
    }

    /**
     * Cache handle methods
     */

    protected static function getCacheKey(string $originName, string $destName): string
    {
        $className = (new \ReflectionClass(SolarSystemRoutes::class))->getShortName();
        return implode('_', [$className, $originName, $destName]);
    }

    protected static function getCachedRoute(string $originName, string $destName): ?array
    {
        if (self::$useCache) {
            $cached = Cache::get(
                self::getCacheKey($originName, $destName)
            );

            if ($cached) {
                return (array) $cached;
            }
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
}
