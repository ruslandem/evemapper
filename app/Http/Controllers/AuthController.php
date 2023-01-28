<?php

namespace App\Http\Controllers;

use App\Services\Api\EveOnline\AuthDataFactory;
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
        $character = Socialite::driver('eveonline')->user();

        $user = User::where('characterId', $character->character_id)->first();

        if ($user) {
            $user->update([
                'token' => $character->token,
                'refreshToken' => $character->refreshToken,
            ]);
        } else {
            $user = User::create([
                'characterId' => $character->character_id,
                'characterName' => $character->character_name,
                'ownerHash' => $character->character_owner_hash,
                'token' => $character->token,
                'refreshToken' => $character->refreshToken,
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
        $user = session()->pull('user');
        $callbackUrl = session()->pull('callbackUrl');

        if ($user === null) {
            return response()->json([
                'message' => 'Unauthorized',
                'error' => 'No user data.'
            ], 401);
        }

        try {
            $data = AuthDataFactory::update($user->refreshToken);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }

        $user->token = $data->getAccessToken();
        $user->refreshToken = $data->getRefreshToken();

        $user->save();

        return $callbackUrl
            ? redirect($callbackUrl)
            : redirect()->action([LocatorController::class, 'locate']);
    }
}
