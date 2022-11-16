<?php

namespace App\Http\Controllers;

use App\Core\EveAuth;
use App\Core\EveLocationHistory;
use App\Core\EveRoute;
use App\Core\EveSolarSystem;

class SystemController extends Controller
{
    protected array $tradeHubs = [
        'Jita',
        'Amarr',
        'Dodixie',
        'Rens',
        'Hek'
    ];

    public function show($system = null)
    {
        $result = [
            'system' => null,
            'jumps' => [],
            'errorMessage' => null,
            'sessionData' => (new EveAuth())->getSessionData(),
            'history' => []
        ];

        if ($system === null) {
            $result['errorMessage'] = 'System not specified';
            return view('system', $result);
        }

        $result['system'] = (new EveSolarSystem())->getByName($system);

        $eveRoute = new EveRoute();
        foreach ($this->tradeHubs as $tradeHub) {
            $result['jumps'][$tradeHub] = count($eveRoute->getRoute($result['system']->solarSystemName, $tradeHub));
        }
        @asort($result['jumps']);

        $result['history'] = (new EveLocationHistory())->get($result['sessionData']['CharacterID']);

        return view('system', $result);
    }
}
