<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EveAuth
{
    public static $adminCharacters = [
        'Khazad Tyori'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $role = 'user')
    {
        if (!Auth::id()) {
            return response()->json([
                'message' => 'Authentication required'
            ], 401);
        }

        if ($role == 'admin') {
            if (
                !in_array(
                    Auth::user()->characterName,
                    self::$adminCharacters
                )
            ) {
                return response()->json([
                    'message' => 'Restricted area'
                ], 401);
            }
        }

        return $next($request);
    }
}
