<?php

namespace App\Core;

use GuzzleHttp\Client;
use Swagger\Client\Eve\Api\RoutesApi;
use Swagger\Client\Eve\ApiException;
use Swagger\Client\Eve\Configuration;

class EveApiRoute
{
    private $accessToken;
    private $apiInstance;
    private $datasource;

    public function __construct(string $accessToken, string $datasource = 'tranquility')
    {
        $this->accessToken = $accessToken;
        $this->datasource = $datasource;

        $this->apiInstance = new RoutesApi(
            new Client(),
            Configuration::getDefaultConfiguration()->setAccessToken($this->accessToken)
        );
    }

    public function getRoute(int $origin, int $destination, string $flag = 'shortest')
    {
        try {
            $result = $this->apiInstance->getRouteOriginDestination(
                $destination,
                $origin,
                null,
                null,
                $this->datasource,
                $flag
            );
        } catch (ApiException $e) {
            $response = json_decode($e->getResponseBody(), true, 512, JSON_THROW_ON_ERROR);

            throw new EveApiException($e->getMessage(), $e->getCode());
        }

        return $result;
    }
}