<?php

namespace App\Core;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Nette\Utils\Random;
use App\Core\Exceptions\EveApiException;

class EveAuth
{
    private const AUTH_URL = 'https://login.eveonline.com/v2/oauth/authorize';
    private const TOKEN_URL = 'https://login.eveonline.com/v2/oauth/token';
    private const VERIFY_URL = 'https://login.eveonline.com/oauth/verify';
    private const SESSION_KEY = 'EveAuth';
    private const AUTH_SCOPE = 'esi-location.read_location.v1';


    private $clientId;
    private $secretKey;
    private $redirectUri;
    private $sessionData;


    public function __construct()
    {
        $this->clientId = Config::get('app.eve.client_id');
        $this->secretKey = Config::get('app.eve.secret_key');
        $this->redirectUri = Config::get('app.eve.callback_url');

        if (!$this->clientId || !$this->secretKey || !$this->redirectUri) {
            throw new EveApiException("clientId or secretKey not found in the app config");
        }

        $this->sessionData = [];

        if (Session::exists(self::SESSION_KEY)) {
            $this->sessionData = Session::get(self::SESSION_KEY);
        }
    }

    /**
     * Generate authentication URL for upcoming redirection.
     * 
     * @return string
     */
    public function getAuthRequestUrl(): string
    {
        $this->sessionData['state'] = Random::generate(32);
        Session::put(self::SESSION_KEY, $this->sessionData);

        $queryParams = [
            'response_type' => 'code',
            'redirect_uri' => $this->redirectUri,
            'client_id' => $this->clientId,
            'scope' => self::AUTH_SCOPE,
            'state' => $this->sessionData['state'],
        ];

        return self::AUTH_URL . '/?' . http_build_query($queryParams);
    }

    /**
     * Handle callback from API.
     * 
     * @param string $authorizationCode Code returned from the API authentication.
     * @return self
     */
    public function getAuthCallback(string $authorizationCode): self
    {
        $accessData = $this->getAccessData($authorizationCode);
        $verificationData = $this->getVerificationData($accessData['access_token']);

        $this->sessionData = array_merge(
            $this->sessionData,
            $accessData,
            $verificationData
        );

        Session::put(self::SESSION_KEY, $this->sessionData);

        return $this;
    }

    /**
     * Refresh expired token.
     * 
     * @return self
     */
    public function refreshAuth(): self
    {
        $updatedData = $this->getUpdatedToken($this->sessionData['refresh_token']);
        $this->sessionData = array_merge(
            $this->sessionData,
            $updatedData
        );

        Session::put(self::SESSION_KEY, $this->sessionData);

        return $this;
    }

    /**
     * Return access token used in other Eve API classes.
     * 
     * @return string|null
     */
    public function getAccessToken(): ?string
    {
        if (array_key_exists('access_token', $this->sessionData)) {
            return $this->sessionData['access_token'];
        }
        
        return null;
    }

    /**
     * Return character ID.
     * 
     * @return int|null
     */
    public function getCharacterId(): ?int
    {
        if ($this->sessionData['CharacterID']) {
            return $this->sessionData['CharacterID'];
        }

        return null;
    }

    public static function isAuthenticated(): bool
    {
        $session = Session::get(self::SESSION_KEY);
        return ($session && $session['CharacterID']);
    }

    /**
     * Return session data with all authenticated information.
     * 
     * @return array
     */
    public function getSessionData(): array
    {
        return $this->sessionData;
    }

    /**
     * Clearing authentication session.
     * 
     * @return void
     */
    public function clearSession(): void
    {
        Session::remove(self::SESSION_KEY);
    }


    protected function getAccessData(string $authorizationCode): array
    {
        $authHeaders = [
            'Authorization: Basic ' . base64_encode($this->clientId . ':' . $this->secretKey),
            'Content-Type: application/x-www-form-urlencoded',
            'Host: login.eveonline.com'
        ];

        $postFields = [
            'grant_type' => 'authorization_code',
            'code' => $authorizationCode
        ];

        $handle = curl_init(self::TOKEN_URL);
        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $authHeaders);
        curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($postFields));
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($handle);

        if ($response === false) {
            throw new EveApiException("Failed to obtain OAuth2 token (" . curl_error($handle) . ")");
        }

        curl_close($handle);

        return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    }

    protected function getVerificationData(string $accessToken): array
    {
        $authHeaders = [
            'Authorization: Bearer ' . $accessToken,
        ];

        $handle = curl_init(self::VERIFY_URL);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $authHeaders);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($handle);

        if ($response === false) {
            throw new EveApiException("Failed to verify OAuth2 token (" . curl_error($handle) . ")");
        }

        curl_close($handle);

        return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    }

    protected function getUpdatedToken(string $refreshToken): array
    {
        $authHeaders = [
            'Authorization: Basic ' . base64_encode($this->clientId . ':' . $this->secretKey),
            'Content-Type: application/x-www-form-urlencoded',
            'Host: login.eveonline.com'
        ];

        $postFields = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken
        ];

        $handle = curl_init(self::TOKEN_URL);
        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $authHeaders);
        curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($postFields));
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($handle);

        if ($response === false) {
            throw new EveApiException("Failed to refresh OAuth2 token (" . curl_error($handle) . ")");
        }

        curl_close($handle);

        return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    }
}
