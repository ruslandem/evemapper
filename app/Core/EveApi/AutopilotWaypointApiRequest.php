<?php

declare(strict_types=1);

namespace App\Core\EveApi;

use App\Exceptions\EveApiException;
use App\Exceptions\EveApiTokenExpiredException;
use App\Models\User;
use App\Services\SolarSystems;
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

        $api = self::getApiInstance('UserInterfaceApi', $user);

        self::log(
            'info',
            "User:{$user->characterId} postUiAutopilotWaypoint {$data['solarSystemName']}"
        );

        $solarSystem = SolarSystems::getByName($data['solarSystemName']);

        try {
            $api->postUiAutopilotWaypoint(
                false,
                false,
                $solarSystem->solarSystemID,
                static::$dataSource,
                $user->token
            );
        } catch (ApiException $e) {
            $errorResponse = self::getErrorResponse($e);

            if ($errorResponse['error'] == 'token is expired') {
                throw new EveApiTokenExpiredException('token is expired');
            }

            throw new EveApiException($e->getMessage(), $e->getCode());
        }

        return [];
    }
}
