<?php

namespace App\Http\Controllers;

use App\Core\EveLocationHistory;
use App\Core\EveRoute;
use App\Core\EveSolarSystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SystemController extends Controller
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

    public function show($system = null)
    {
        $result = [
            'system' => null,
            'jumps' => [],
            'errorMessage' => null,
            'history' => []
        ];

        if (empty($system)) {
            $result['errorMessage'] = 'No system specified';
            return view('system', $result);
        }

        $result['system'] = (new EveSolarSystem())->getByName($system);

        if ($result['system']) {
            $eveRoute = new EveRoute();
            foreach ($this->tradeHubs as $tradeHub) {
                $result['jumps'][$tradeHub] = count($eveRoute->getRoute($result['system']->solarSystemName, $tradeHub));
            }
            @asort($result['jumps']);

            $result['history'] = (new EveLocationHistory())->get(Auth::id());
        }

        return view('system', $result);
    }
}
