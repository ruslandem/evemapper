<?php

namespace App\Http\Controllers;

use App\Core\EveApiRoute;
use App\Core\EveAuth;
use App\Core\EveRoute;
use Illuminate\Support\Facades\DB;

class RoutesController extends Controller
{
    public function route()
    {
        $origin = 'Jita';
        $destination = 'Amarr';

        $conn = DB::connection('sqlite');
        $originSystem = $conn->table('mapSolarSystems')
            ->where('solarSystemName', '=', $origin)
            ->first();
        $destinationSystem = $conn->table('mapSolarSystems')
            ->where('solarSystemName', '=', $destination)
            ->first();

        if (!$originSystem || !$destinationSystem) {
            throw new \Exception('origin or target system not found');
        }

        // $api = new EveApiRoute(
        //     (new EveAuth())->getAccessToken()
        // );

        // $result = $api->getRoute($originSystem->solarSystemID, $destinationSystem->solarSystemID, 'shortest');

        // dd($this->systemIdtoNames($result));

        $api = new EveRoute(
            DB::connection('sqlite')
        );
        $route = $api->getRoute('Jita', 'Amarr');
        dd($route);
    }

    private function systemIdtoNames(iterable $systems): array
    {
        $conn = DB::connection('sqlite');
        $result = [];
        foreach ($systems as $systemId) {
            $item = $conn->table('mapSolarSystems')
                ->where('solarSystemID', '=', $systemId)
                ->first();
            $result[] = $item->solarSystemName . ' (' . round($item->security, 1) . ') [' . $systemId . ']';
        }

        return $result;
    }
}
