<?php

declare(strict_types=1);

namespace App\Core\EveApi;

use App\Core\EveSolarSystem;
use App\Core\Exceptions\EveApiException;
use App\Core\Exceptions\EveApiTokenExpiredException;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Swagger\Client\Eve\ApiException;

/**
 * Add waypoint to character's autopilot.
 */
class AutopilotWaypointApiRequest extends AbstractApiRequest
{
    /**
     * @param User $user
     * @param array|null $data
     * @throws EveApiTokenExpiredException
     * @throws EveApiException
     * @return array
     */
    public static function get(User $user, ?array $data): array
    {
        if (!$data['solarSystemName']) {
            throw new EveApiException('solarSystemName is not set');
        }

        $api = self::getApiInstance('userInterfaceApi', $user);

        if (Config::get('app.api.log', true)) {
            Log::channel('api')->info(
                "User:{$user->characterId} postUiAutopilotWaypoint {$data['solarSystemName']}"
            );
        }

        try {
            $api->postUiAutopilotWaypoint(
                false,
                false,
                EveSolarSystem::getByName($data['solarSystemName']),
                static::$dataSource,
                $user->token
            );
        } catch (ApiException $e) {
            $errorResponse = json_decode(
                $e->getResponseBody(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            if ($errorResponse['error'] == 'token is expired') {
                throw new EveApiTokenExpiredException('token is expired');
            }

            throw new EveApiException($e->getMessage(), $e->getCode());
        }

        return [];
    }
}
