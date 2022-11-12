<?php

namespace App\Http\Controllers;

use App\Core\EveAuth;
use App\Core\EveLocationHistory;
use App\Core\EveSolarSystem;

class SystemController extends Controller
{
    public function show($system = null)
    {
        $api = new EveAuth();
        $sessionData = $api->getSessionData();

        if ($system === null) {
            return view('system', [
                'system' => null,
                'errorMessage' => 'System not specified',
                'sessionData' => $sessionData
            ]);
        }

        try {
            $solarSystem = new EveSolarSystem(0, $system);
            $found = $solarSystem->getData();
        } catch (\Throwable $th) {
            $found = null;
        }

        $history = EveLocationHistory::get($sessionData['CharacterID']);

        return view('system', [
            'system' => $found,
            'errorMessage' => '',
            'sessionData' => $sessionData,
            'history' => $history
        ]);
    }
}
