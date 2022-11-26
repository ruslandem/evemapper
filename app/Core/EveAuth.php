<?php

namespace App\Core;

use Illuminate\Support\Facades\Config;
use App\Core\Exceptions\EveApiException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\RequestOptions;

class EveAuth
{
    public static function refreshAuthToken(string $code): array
    {
        $clientId = Config::get('app.eve.client_id');
        $secretKey = Config::get('app.eve.secret_key');

        if (!$clientId || !$secretKey) {
            throw new EveApiException("clientId or secretKey not found in the config");
        };

        $response = (new HttpClient())->request(
            'POST',
            'https://login.eveonline.com/v2/oauth/token',
            [
                RequestOptions::HEADERS => [
                    'Authorization' => 'Basic ' . base64_encode($clientId . ':' . $secretKey),
                ],
                RequestOptions::FORM_PARAMS => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $code,
                ],
            ]
        );

        return json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }
}
