<?php

declare(strict_types=1);

namespace App\Core\EveApi;

class AuthData
{
    protected ?string $accessToken;
    protected ?int $expiresIn;
    protected ?string $tokenType;
    protected ?string $refreshToken;

    /**
     * @throws JsonException
     * @param string|null $oAuthResponse
     */
    function __construct(?string $oAuthResponse = null)
    {
        if ($oAuthResponse) {
            $json = json_decode($oAuthResponse, true, 512, JSON_THROW_ON_ERROR);

            $this->setProperty('accessToken', $json['access_token']);
            $this->setProperty('expiresIn', $json['expires_in']);
            $this->setProperty('tokenType', $json['token_type']);
            $this->setProperty('refreshToken', $json['refresh_token']);
        }
    }

    /**
     * Get the value of accessToken
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * Get the value of expiresIn
     */
    public function getExpiresIn(): ?string
    {
        return $this->expiresIn;
    }

    /**
     * Get the value of tokenType
     */
    public function getTokenType(): ?string
    {
        return $this->tokenType;
    }

    /**
     * Get the value of refreshToken
     */
    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    /**
     * Set authentication response property.
     * 
     * @param string $name property name
     * @param mixed|null $value value acquired from response
     * @throws \InvalidArgumentException if property does not exist
     * @throws \UnexpectedValueException if value in response is null
     * @return $this
     */
    public function setProperty(string $name, $value): self
    {
        if (!property_exists($this, $name)) {
            throw new \InvalidArgumentException("property {$name} not found");
        }

        if ($value === null) {
            throw new \UnexpectedValueException("failed to acquire {$name}");
        }

        $this->$name = $value;

        return $this;
    }
}
