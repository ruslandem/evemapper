<?php

namespace App\Core;

use App\Core\Exceptions\EveApiException;
use App\Core\Exceptions\EveApiTokenExpiredException;
use GuzzleHttp\Client;
use Swagger\Client\Eve\Api\LocationApi;
use Swagger\Client\Eve\ApiException;
use Swagger\Client\Eve\Configuration;
use Swagger\Client\Eve\Model\GetCharactersCharacterIdLocationOk;

class EveLocationApi
{
    private $accessToken;
    private $apiInstance;
    private $datasource;

    public function __construct(string $accessToken, string $datasource = 'tranquility')
    {
        $this->accessToken = $accessToken;
        $this->datasource = $datasource;

        $this->apiInstance = new LocationApi(
            new Client(),
            Configuration::getDefaultConfiguration()->setAccessToken($this->accessToken)
        );
    }

    /**
     * Return solar system ID for the character.
     * 
     * @param int $characterId
     * @return int
     */
    public function getCharacterLocation(int $characterId): int
    {
        try {
            $result = $this->apiInstance->getCharactersCharacterIdLocation(
                $characterId,
                $this->datasource,
                '',
                $this->accessToken
            );
        } catch (ApiException $e) {
            $response = json_decode($e->getResponseBody(), true, 512, JSON_THROW_ON_ERROR);

            if ($response['error'] == 'token is expired') {
                throw new EveApiTokenExpiredException('token is expired');
            }

            throw new EveApiException($e->getMessage(), $e->getCode());
        }

        if (!$result instanceof GetCharactersCharacterIdLocationOk) {
            throw new EveApiException('failed to get character location');
        }

        return $result['solar_system_id'];
    }
}
