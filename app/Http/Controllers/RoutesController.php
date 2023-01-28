<?php

namespace App\Http\Controllers;

use App\Services\Api\EveOnline\AutopilotWaypointApiRequest;
use App\Exceptions\EveApiTokenExpiredException;
use App\Exceptions\EveRouteNotFoundException;
use App\Services\SolarSystemRoutes;
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
        $request->validate([
            'waypoints' => 'required',
        ]);
        
        $waypoints = [];
        if ($request->input('waypoints')) {
            $waypoints = explode(',', $request->input('waypoints'));
        }

        return view('route', compact('waypoints'));
    }

    public function buildRoute(Request $request)
    {
        $request->validate([
            'waypoints' => 'required',
        ]);

        $route = [];
        $waypoints = $request->input('waypoints', []);

        try {
            $route = SolarSystemRoutes::getWaypointsRoute($waypoints, true);
        } catch (EveRouteNotFoundException $e) {
            return response()->json([
                'message' => 'Failed to build route'
            ], 401);
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
