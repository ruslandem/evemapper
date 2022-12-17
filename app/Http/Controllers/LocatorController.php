<?php

namespace App\Http\Controllers;

use App\Core\EveLocationHistory;
use App\Core\EveRoute;
use App\Core\EveSolarSystem;
use Illuminate\Http\Request;
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

    public function list(Request $request)
    {
        $systems = [];

        $searchText = trim($request->input('search'));

        if (!empty($searchText)) {
            $systems = (new EveSolarSystem())->search($searchText);
        }

        return response()->json([
            'systems' => array_column($systems, 'solarSystemName')
        ]);
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
}
