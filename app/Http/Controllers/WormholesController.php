<?php

namespace App\Http\Controllers;

use App\Core\EveApi;
use App\Core\EveAuth;
use App\Core\EveSolarSystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ItemNotFoundException;

class WormholesController extends Controller
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

        return view('system', [
            'system' => $found,
            'errorMessage' => 'Unknown solar system',
            'sessionData' => $sessionData
        ]);
    }
}
