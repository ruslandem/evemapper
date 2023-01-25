<?php

namespace App\Http\Controllers;

use App\Core\EveApi\LocationApiRequest;
use App\Core\EveLocationHistory;
use App\Core\EveRoute;
use App\Core\EveSolarSystem;
use App\Core\Exceptions\EveApiException;
use App\Core\Exceptions\EveApiTokenExpiredException;
use App\Enums\TradeHubs;
use App\Models\SolarSystem;
use Illuminate\Support\Facades\Auth;

class LocatorController extends Controller
{
    protected array $tradeHubs = ['Jita', 'Amarr', 'Dodixie', 'Rens', 'Hek'];

    public function __construct()
    {
        $this->middleware('eve.auth');
    }

    public function list($search = null)
    {
        $systems = [];

        $searchText = trim($search);

        if (!empty($searchText)) {
            $systems = EveSolarSystem::search($searchText);
        }

        return array_column($systems, 'solarSystemName');
    }

    public function get(string $systemName)
    {
        $system = SolarSystem::where(['solarSystemName' => $systemName])
            ->with(['region', 'constellation', 'wormhole', 'jumps'])
            ->first();

        if (!$system) {
            return response()->json([
                'error' => 'Solar system not found or empty'
            ], 400);
        }

        foreach (TradeHubs::cases() as $hub) {
            $jumps[$hub->name] = count(
                EveRoute::getRoute($system->solarSystemName, $hub->name)
            );
        }
        @asort($jumps);

        $history = EveLocationHistory::get(Auth::id());

        return [
            'system' => $system->toArray(),
            'jumps' => $jumps,
            'history' => $history
        ];
    }

    public function getLocationsHistory()
    {
        return EveLocationHistory::get(Auth::id());
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

            $system = SolarSystem::find($location['solar_system_id']);
            // write history record
            // EveLocationHistory::write(
            //     $user->characterId,
            //     $system->solarSystemName
            // );
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
