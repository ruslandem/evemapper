<?php

declare(strict_types=1);

namespace App\Services\Api\EveOnline;

use App\Models\User;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\RequestOptions as HttpRequestOptions;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class AuthDataFactory
{
    public static string $authUrl = 'https://login.eveonline.com/v2/oauth/token';

    public static function update(string $refreshToken): AuthData
    {
        $clientId = Config::get('app.eve.client_id')
            ?? throw new \Exception('client_id not found in config');

        $secretKey = Config::get('app.eve.secret_key')
            ?? throw new \Exception('secret_key not found in config');

        if (Config::get('app.api.log', true)) {
            Log::channel('api')->info(static::$authUrl . ' (update)');
        }

        $response = (new HttpClient())->request(
            'POST',
            static::$authUrl,
            [
                HttpRequestOptions::HEADERS => [
                    'Authorization' => 'Basic ' .
                        base64_encode($clientId . ':' . $secretKey),
                ],
                HttpRequestOptions::FORM_PARAMS => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refreshToken,
                ],
            ]
        );

        return new AuthData((string) $response->getBody());
    }

    public static function fetch(User $user): AuthData
    {
        $authData = new AuthData();

        return $authData
            ->setProperty('accessToken', $user->token)
            ->setproperty('refreshToken', $user->refreshToken);
    }
}
