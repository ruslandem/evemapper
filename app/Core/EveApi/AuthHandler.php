<?php

declare(strict_types=1);

namespace App\Core\EveApi;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\RequestOptions as HttpRequestOptions;

class AuthHandler
{
    protected ?AuthData $authData = null;

    public function __construct(
        public string $clientId,
        public string $secretKey,
        public string $authUrl = 'https://login.eveonline.com/v2/oauth/token',
    ) {
        if (!$clientId || !$secretKey) {
            throw new \InvalidArgumentException('client id and/or key are not specified');
        };
    }

    /**
     * Update refresh token using OAuth2.
     * 
     * @param string $refreshToken
     * @return $this
     */
    public function update(string $refreshToken): self
    {
        $response = (new HttpClient())->request(
            'POST',
            $this->authUrl,
            [
                HttpRequestOptions::HEADERS => [
                    'Authorization' => 'Basic ' .
                        base64_encode($this->clientId . ':' . $this->secretKey),
                ],
                HttpRequestOptions::FORM_PARAMS => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refreshToken,
                ],
            ]
        );

        $this->authData = new AuthData((string) $response->getBody());

        return $this;
    }

    /**
     * @return AuthData|null
     */
    public function getAuthData(): ?AuthData
    {
        return $this->authData;
    }
}
