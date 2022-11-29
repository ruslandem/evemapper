<?php

namespace App\Http\Controllers;

use App\Core\Exceptions\EveApiTokenExpiredException;
use App\Core\EveAuth;
use App\Core\EveLocationApi;
use App\Core\EveLocationHistory;
use App\Core\EveSolarSystem;
use App\Core\EveWormholeClasses;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class EveController extends Controller
{
    protected static $scopes = ['esi-location.read_location.v1', 'esi-ui.write_waypoint.v1'];

    public function __construct()
    {
        JWT::$leeway = 60;
    }

    public function main()
    {
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

        $damageTypes = [
            ["Blood Raider", "EM/Thermal", "EM"],
            ["Guristas", "Kinetic/Thermal", "Kinetic"],
            ["Sanshas", "EM/Thermal", "EM"],
            ["Serpentis", "Thermal/Kinetic", "Kinetic"],
            ["Angel", "Explosive/Kinetic", "Explosive"],
            ["Mordus", "Kinetic/Thermal", "Kinetic"],
            ["Rogue Drones", "Explosive/Kinetic", "EM"],
            ["Mercenary", "EM/Thermal", "Thermal"],
            ["Minmatar", "Thermal", " Explo"],
            ["Gallente", "Kinetic/Thermal", "Thermal"],
            ["Amarr", "EM/Thermal", "Thermal"],
            ["Caldari", "Kinetic/Thermal", "Kinetic"],
        ];

        return view('main', compact('classes', 'damageTypes'));
    }

    public function auth()
    {
        return Socialite::driver('eveonline')
            ->scopes(self::$scopes)
            ->redirect();
    }



    public function callback(Request $request)
    {
        $eveUser = Socialite::driver('eveonline')->user();

        $user = User::where('characterId', $eveUser->character_id)->first();

        if ($user) {
            $user->update([
                'token' => $eveUser->token,
                'refreshToken' => $eveUser->refreshToken,
            ]);
        } else {
            $user = User::create([
                'characterId' => $eveUser->character_id,
                'characterName' => $eveUser->character_name,
                'ownerHash' => $eveUser->character_owner_hash,
                'token' => $eveUser->token,
                'refreshToken' => $eveUser->refreshToken,
                'scopes' => self::$scopes,
            ]);
        }

        Auth::login($user, true);

        return redirect('/');
    }

    public function locate()
    {
        $user = Auth::user();

        try {
            $locationApi = new EveLocationApi($user->token);
            $solarSystemId = $locationApi->getCharacterLocation($user->characterId);
        } catch (EveApiTokenExpiredException $e) {
            return $this->update($user);
        }

        $solarSystem = new EveSolarSystem();
        $data = $solarSystem->getById($solarSystemId);

        // logging location
        (new EveLocationHistory())->write($user->characterId, $data->solarSystemName);

        return response()->json([
            'solarSystemName' => $data->solarSystemName
        ]);
    }

    public function update(User $user)
    {
        try {
            $data = EveAuth::refreshAuthToken($user->refreshToken);
            if (empty($data)) {
                throw new \UnexpectedValueException('empty token data received');
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }

        $user->token = $data['access_token'];
        $user->refreshToken = $data['refresh_token'];

        $user->save();

        return redirect()->action([EveController::class, 'locate']);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function waypoint(Request $request)
    {
        $user = Auth::user();

        try {
            $api = new EveLocationApi($user->token);
            $api->addAutopilotWaypoint(
                $request->input('system')
            );

        } catch (EveApiTokenExpiredException $e) {
            return $this->update($user);
        }
    }
}
