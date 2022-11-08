<?php

namespace App\Http\Controllers;

use App\Core\EveApiTokenExpiredException;
use App\Core\EveAuth;
use App\Core\EveLocationApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ItemNotFoundException;
use Swagger\Client\Eve\Api\CharacterApi;
use Swagger\Client\Eve\Model\GetCharactersCharacterIdLocationOk;

class EveController extends Controller
{
    public function main()
    {
        $api = new EveAuth();
        $sessionData = $api->getSessionData();
        
        return view('main', compact('sessionData'));
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

        // searching for solar system name in our database
        $systems = DB::table('wormhole_systems')->where('id', '=', $solarSystemId)->get();

        try {
            $found = $systems->firstOrFail();
        } catch (ItemNotFoundException $e) {
            return view('system', [
                'system' => null,
                'errorMessage' => 'Not found (' . $solarSystemId . ')'
            ]);
        }

        return redirect('/system/' . $found->system);
    }

    public function update()
    {
        $api = new EveAuth();
        $api->refreshAuth();

        return redirect()->action([EveController::class, 'locate']);
    }

    public function clear()
    {
        $api = new EveAuth();
        $api->clearSession();

        return redirect('/');
    }
}
