<?php

namespace App\Http\Controllers;

use App\Core\EveApi\LocationApiRequest;
use App\Core\Exceptions\EveApiException;
use App\Core\Exceptions\EveApiTokenExpiredException;
use App\Enums\TradeHubs;
use App\Models\SolarSystem;
use App\Services\SolarSystemRoutes;
use App\Services\SolarSystems;
use App\Services\UserLocationHistory;
use Illuminate\Support\Facades\Auth;

class LocatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('eve.auth');
    }

    public function list($search = null)
    {
        $systems = [];

        $searchText = trim($search);
        if (!empty($searchText)) {
            $systems = SolarSystems::filterByName($searchText);
        }

        return $systems;
    }

    public function get(string $systemName)
    {
        $user = Auth::user();

        if ($user === null) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        /**
         * get solar system information
         */
        $system = SolarSystem::where(['solarSystemName' => $systemName])
            ->with(['region', 'constellation', 'wormhole', 'jumps'])
            ->first();

        if (!$system) {
            return response()->json([
                'error' => 'Solar system not found or empty'
            ], 400);
        }

        /**
         * create jumps array
         */
        $jumps = [];
        foreach (TradeHubs::cases() as $hub) {
            $jumps[$hub->name] = SolarSystemRoutes::getRouteLength(
                $system->solarSystemName,
                $hub->name
            );
        }
        @asort($jumps);

        /**
         * get location history
         */
        $history = UserLocationHistory::getHistory($user);

        return [
            'system' => $system->toArray(),
            'jumps' => $jumps,
            'history' => $history
        ];
    }

    public function getLocationsHistory()
    {
        $user = Auth::user();

        if ($user === null) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        return UserLocationHistory::getHistory($user);
    }

    public function locate()
    {
        $user = Auth::user();

        if ($user === null) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        try {
            // get character location
            $location = LocationApiRequest::get($user);
            // get solar system info
            $system = SolarSystem::find($location['solar_system_id']);
            // write history record
            UserLocationHistory::addHistory($user, $system);
        } catch (EveApiTokenExpiredException $e) {
            // redirect to update auth token
            return redirect()->route('auth-update')
                ->with('user', $user);
        } catch (EveApiException $e) {
            // API error
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }

        return response()->json($system->solarSystemName);
    }
}
