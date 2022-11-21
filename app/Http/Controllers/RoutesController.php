<?php

namespace App\Http\Controllers;

use App\Core\EveAuth;
use App\Core\EveRoute;
use App\Core\EveSolarSystem;
use App\Core\Exceptions\EveRouteNotFoundException;
use Illuminate\Http\Request;

class RoutesController extends Controller
{
    public function route()
    {
        $api = new EveAuth();
        $sessionData = $api->getSessionData();

        return view('route', compact('sessionData'));
    }

    public function buildRoute(Request $request)
    {
        $waypoints = $request->input('waypoints', []);

        $api = new EveRoute();

        try {
            $route = $api->getWaypointsRoute($waypoints);
        } catch (EveRouteNotFoundException $e) {
            return response()->json(
                [
                    'route' => [],
                    'info' => [],
                    'error' => $e->getMessage()
                ]
            );
        }

        $info = [];
        $api = new EveSolarSystem();
        foreach ($route as $key => $value) {
            $info[$key] = $api->getByNames($route[$key]);
        }

        return response()->json(
            ['route' => $route, 'info' => $info]
        );
    }
}
