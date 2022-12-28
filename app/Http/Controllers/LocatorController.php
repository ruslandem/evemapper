<?php

namespace App\Http\Controllers;

use App\Core\EveLocationApi;
use App\Core\EveLocationHistory;
use App\Core\EveRoute;
use App\Core\EveSolarSystem;
use App\Core\Exceptions\EveApiTokenExpiredException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LocatorController extends Controller
{
    protected array $tradeHubs = [
        'Jita',
        'Amarr',
        'Dodixie',
        'Rens',
        'Hek'
    ];

    public function __construct()
    {
        $this->middleware('eve.auth');
    }

    public function list($search = null)
    {
        $systems = [];

        $searchText = trim($search);

        if (!empty($searchText)) {
            $systems = (new EveSolarSystem())->search($searchText);
        }

        return array_column($systems, 'solarSystemName');
    }

    public function get($system = null)
    {
        $result = [
            'system' => null,
            'jumps' => [],
            'errorMessage' => null,
            'history' => (new EveLocationHistory())->get(Auth::id()),
            'signatures' => [],
        ];

        if (empty($system)) {
            $result['errorMessage'] = 'No system specified';
            return $result;
        }

        $result['system'] = (new EveSolarSystem())->getByName($system);

        if ($result['system']) {
            $eveRoute = new EveRoute();
            foreach ($this->tradeHubs as $tradeHub) {
                $result['jumps'][$tradeHub] = count(
                    $eveRoute->getRoute($result['system']->solarSystemName, $tradeHub)
                );
            }
            @asort($result['jumps']);
        }

        return $result;
    }

    public function getLocationsHistory()
    {
        $debug = Auth::user()->toArray();
        Log::debug(json_encode($debug, JSON_PRETTY_PRINT));

        return (new EveLocationHistory())->get(Auth::id());
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
            $locationApi = new EveLocationApi($user->token);
            $solarSystemId = $locationApi->getCharacterLocation($user->characterId);
        } catch (EveApiTokenExpiredException $e) {

            return redirect()
                ->route('auth-update')
                ->with('user', $user);
        }

        $solarSystem = new EveSolarSystem();
        $data = $solarSystem->getById($solarSystemId);

        // logging location
        (new EveLocationHistory())->write($user->characterId, $data->solarSystemName);

        return $data->solarSystemName;
    }
}
