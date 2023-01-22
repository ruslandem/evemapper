<?php

declare(strict_types=1);

namespace App\Core\EveApi;

use App\Models\User;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Swagger\Client\Eve\ApiException;
use Swagger\Client\Eve\Configuration;

/**
 * Abstract class for API requests using Swagger Eve Online Client.
 * 
 * @package EveMapper
 */
abstract class AbstractApiRequest
{
    /**
     * Data-source (game server) for Eve Online API requests
     * @var string
     */
    public static string $dataSource = 'tranquility';
    /**
     * Configuration key for enabling API requests logging
     * @var string
     */
    public static string $configKey = 'app.api.log';
    /**
     * Log channel
     * @var string
     */
    public static string $logChannel = 'api';


    /**
     * Abstract function to make API request
     * @param User $user
     * @param array|null $data
     * @return array
     * @abstract
     */
    abstract public static function get(User $user, ?array $data): array;


    /**
     * Write log message with API logging channel
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

    /**
     * Get decoded error response from API Exception
     * @param \Swagger\Client\Eve\ApiException $e
     * @return mixed
     */
    protected static function getErrorResponse(ApiException $e)
    {
        return json_decode(
            $e->getResponseBody(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }

    /**
     * Get instance of swagger API class
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

    /**
     * Converts ArrayAccess into array
     * @param \ArrayAccess $array
     * @return array
     */
    protected static function toArray(\ArrayAccess $array): array
    {
        return json_decode(
            json_encode($array),
            true
        );
    }
}
