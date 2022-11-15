<?php

namespace App\Http\Controllers;

use App\Core\Exceptions\EveApiTokenExpiredException;
use App\Core\EveAuth;
use App\Core\EveLocationApi;
use App\Core\EveLocationHistory;
use App\Core\EveSolarSystem;
use App\Core\EveWormholeClasses;
use Illuminate\Http\Request;

class EveController extends Controller
{
    public function main()
    {
        $api = new EveAuth();
        $sessionData = $api->getSessionData();

        $wormholeClasses = new EveWormholeClasses();
        $classes = [
            'C1' => $wormholeClasses->getList(1),
            'C2' => $wormholeClasses->getList(2),
            'C3' => $wormholeClasses->getList(3),
            'C4' => $wormholeClasses->getList(4),
            'C5' => $wormholeClasses->getList(5),
            'C6' => $wormholeClasses->getList(6),
            'C13' => $wormholeClasses->getList(13),
            'High' => $wormholeClasses->getList(7),
            'Low' => $wormholeClasses->getList(8),
            'Null' => $wormholeClasses->getList(9),
            'Thera' => $wormholeClasses->getList(12),
        ];
        
        return view('main', compact('sessionData', 'classes'));
    }

    public function auth()
    {
        $api = new EveAuth();
        $requestUrl = $api->getAuthRequestUrl();

        return redirect($requestUrl);
    }

    public function callback(Request $request)
    {
        $api = new EveAuth();
        $api->getAuthCallback(
            $request->input('code')
        );

        return redirect('/');
    }

    public function locate()
    {
        $api = new EveAuth();
        $token = $api->getAccessToken();

        if ($token === null) {
            return redirect('/auth');
        }

        $character = $api->getCharacterId();

        if ($character === null) {
            throw new \Exception('failed to get character ID');
        }

        try {
            $locationApi = new EveLocationApi($token);
            $solarSystemId = $locationApi->getCharacterLocation($character);
        } catch (EveApiTokenExpiredException $e) {
            return $this->update();
        }

        $solarSystem = new EveSolarSystem();
        $data = $solarSystem->getById($solarSystemId);

        // logging location
        (new EveLocationHistory())->write($character, $data->solarSystemName);

        return response()->json([
            'solarSystemName' => $data->solarSystemName
        ]);
    }

    public function update()
    {
        $api = new EveAuth();
        $api->refreshAuth();

        return redirect()->action([EveController::class, 'locate']);
    }

    public function logout()
    {
        $api = new EveAuth();
        $api->clearSession();

        return redirect('/');
    }
}
