<?php

namespace App\Http\Controllers;

use App\Core\EveApi\AutopilotWaypointApiRequest;
use App\Core\EveRoute;
use App\Core\EveSolarSystem;
use App\Core\Exceptions\EveApiTokenExpiredException;
use App\Core\Exceptions\EveRouteNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoutesController extends Controller
{
    public function __construct()
    {
        $this->middleware('eve.auth');
    }

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
            $route = EveRoute::getWaypointsRoute($waypoints);
        } catch (EveRouteNotFoundException $e) {
            return response()->json([
                'message' => 'Failed to build route'
            ], 401);
        }

        foreach ($route as &$waypointRoute) {
            foreach ($waypointRoute as &$waypoint) {
                $info = EveSolarSystem::getByName($waypoint);
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

    public function waypoint(Request $request)
    {
        $validated = $request->validate([
            'system' => 'required|string',
        ]);

        $user = Auth::user();

        if ($user === null) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        try {
            AutopilotWaypointApiRequest::get($user, [
                'solarSystemName' => $validated['system']
            ]);
        } catch (EveApiTokenExpiredException $e) {
            return redirect()
                ->action([AuthController::class, 'update'])
                ->with('user', $user)
                ->with('callbackUrl', '/api/addAutopilotWaypoint');
        }
    }
}
