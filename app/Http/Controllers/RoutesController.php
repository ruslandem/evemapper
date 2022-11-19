<?php

namespace App\Http\Controllers;

use App\Core\EveApiRoute;
use App\Core\EveAuth;
use App\Core\EveRoute;
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
        $route = $api->getWaypointsRoute($waypoints);

        return response()->json(
            ['route' => $route]
        );
    }
}
