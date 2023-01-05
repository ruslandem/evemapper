<?php

namespace App\Http\Controllers;

use App\Core\EveApi\LocationApiRequest;
use App\Core\EveLocationHistory;
use App\Core\EveRoute;
use App\Core\EveSolarSystem;
use App\Core\Exceptions\EveApiException;
use App\Core\Exceptions\EveApiTokenExpiredException;
use Illuminate\Support\Facades\Auth;

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
            $systems = EveSolarSystem::search($searchText);
        }

        return array_column($systems, 'solarSystemName');
    }

    public function get(string $system)
    {
        $result = [
            'system' => null,
            'jumps' => [],
            'errorMessage' => null,
            'history' => EveLocationHistory::get(Auth::id()),
            'signatures' => [],
        ];

        if (empty($system)) {
            $result['errorMessage'] = 'No system specified';
            return $result;
        }

        $result['system'] = EveSolarSystem::getByName($system);

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
            // get current character location
            $location = LocationApiRequest::get($user);

            // get solar system info
            $solarSystem = EveSolarSystem::getById(
                $location['solar_system_id']
            );

            // log current location
            EveLocationHistory::write(
                $user->characterId,
                $solarSystem->solarSystemName
            );
        } catch (EveApiTokenExpiredException $e) {
            return redirect()->route('auth-update')->with('user', $user);
        } catch (EveApiException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }

        return $solarSystem->solarSystemName;
    }
}
