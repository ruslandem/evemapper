<?php

declare(strict_types=1);

namespace App\Core\EveApi;

use App\Models\User;
use ArrayAccess;
use GuzzleHttp\Client as HttpClient;
use Swagger\Client\Eve\Configuration;

abstract class AbstractApiRequest
{
    public static string $dataSource = 'tranquility';

    abstract public static function get(User $user, ?array $data): array;

    /**
     * Get instance of swagger api class.
     * 
     * @param string $apiName
     * @param User $user
     * @return null|class
     */
    protected static function getApiInstance(string $apiName, User $user)
    {
        $apiConfig = Configuration::getDefaultConfiguration()
            ->setAccessToken($user->token);

        $className = '\Swagger\Client\Eve\Api\\' . $apiName;

        return class_exists($className)
            ? new $className(new HttpClient(), $apiConfig)
            : null;
    }

    protected static function toArray(ArrayAccess $array): array
    {
        return json_decode(
            json_encode($array),
            true
        );
    }
}
