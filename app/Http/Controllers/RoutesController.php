<?php

namespace App\Http\Controllers;

use App\Core\EveRoute;
use App\Core\EveSolarSystem;
use App\Core\Exceptions\EveRouteNotFoundException;
use Illuminate\Http\Request;

class RoutesController extends Controller
{
    public function route(Request $request)
    {
        $waypoints = [];
        if ($request->input('waypoints')) {
            $waypoints = explode(',', $request->input('waypoints'));
        }

        return view('route', compact('waypoints'));
    }

    public function buildRoute(Request $request)
    {
        $route = [];
        $waypoints = $request->input('waypoints', []);

        try {
            $route = (new EveRoute())->getWaypointsRoute($waypoints);
        } catch (EveRouteNotFoundException $e) {
            return response()->json([
                'message' => 'Failed to build route'
            ], 401);
        }

        $api = new EveSolarSystem();

        foreach ($route as &$waypointRoute) {
            foreach ($waypointRoute as &$waypoint) {
                $info = $api->getByName($waypoint);
                $waypoint = [
                    'solarSystemID' => $info->solarSystemID,
                    'solarSystemName' => $info->solarSystemName,
                    'constellationName' => $info->constellationName,
                    'regionName' => $info->regionName,
                    'security' => $info->security,
                    'rats' => $info->rats,
                ];
            }
        }

        return response()->json($route);
    }
}
