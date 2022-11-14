<?php

namespace App\Http\Controllers;

use App\Core\EveAuth;
use App\Core\EveLocationHistory;
use App\Core\EveRoute;
use App\Core\EveSolarSystem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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

        $hubsJumps = [];

        try {
            $solarSystem = new EveSolarSystem(0, $system);
            $found = $solarSystem->getData();



            //
            //if (!$hubsJumps) {


            //Cache::put('hubsJumps_' . $found->solarSystemName, $hubsJumps);
            //}
        } catch (\Throwable $th) {
            $found = null;
        }

        $hubsJumps = Cache::get('hubsJumps_' . $found->solarSystemName);
        if ($hubsJumps === null) {
            $eveRoute = new EveRoute(
                DB::connection('sqlite')
            );

            $hubs = ['Jita', 'Amarr', 'Dodixie', 'Rens', 'Hek'];
            $hubsJumps = [];
            foreach ($hubs as $hub) {
                $hubsJumps[$hub] = count($eveRoute->getRoute($found->solarSystemName, $hub));
            }

            asort($hubsJumps);

            Cache::put('hubsJumps_' . $found->solarSystemName, $hubsJumps);
        }

        $history = EveLocationHistory::get($sessionData['CharacterID']);

        return view('system', [
            'system' => $found,
            'hubsJumps' => $hubsJumps,
            'errorMessage' => '',
            'sessionData' => $sessionData,
            'history' => $history
        ]);
    }
}
