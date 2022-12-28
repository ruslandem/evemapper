<?php

namespace App\Http\Controllers;

use App\Core\EveAuth;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Eve api scopes
     */
    protected static $scopes = [
        'esi-location.read_location.v1',
        'esi-ui.write_waypoint.v1'
    ];

    public function __construct()
    {
        JWT::$leeway = 60;
    }


    /**
     * Redirects to EvE SSO authentication page.
     * 
     * @throws \InvalidArgumentException
     * @return \Illuminate\Http\Response
     */
    public function auth()
    {
        return Socialite::driver('eveonline')
            ->scopes(self::$scopes)
            ->redirect();
    }

    /**
     * Callback for Eve SSO authentication.
     * 
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Provides logging out and clear session.
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }


    /**
     * Updates expired Eve SSO token for the specified user.
     * 
     * @return Illuminate\Http\Response
     */
    public function update()
    {
        $user = session()->pull('userData');

        if ($user === null) {
            return response()->json([
                'message' => 'Unauthorized',
                'error' => 'No user data.',
            ], 401);
        }

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

        return redirect()->action([LocatorController::class, 'locate']);
    }
}
