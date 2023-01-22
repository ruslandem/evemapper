<?php

declare(strict_types=1);

namespace App\Core\EveApi;

use App\Models\User;
use ArrayAccess;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Swagger\Client\Eve\ApiException;
use Swagger\Client\Eve\Configuration;

abstract class AbstractApiRequest
{
    public static string $dataSource = 'tranquility';

    public static string $configKey = 'app.api.log';
    public static string $logChannel = 'api';


    /**
     * @param string $level
     * @param string|\Stringable $message
     * @param array $context
     * @throws \Psr\Log\InvalidArgumentException
     * @return void
     */
    protected static function log(string $level, string|\Stringable $message, array $context = []): void
    {
        if (Config::get(self::$configKey, true)) {
            Log::channel(self::$logChannel)->log($level, $message, $context);
        }
    }

    protected static function getErrorResponse(ApiException $e)
    {
        return json_decode(
            $e->getResponseBody(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }

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
