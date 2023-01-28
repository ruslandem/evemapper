<?php

declare(strict_types=1);

namespace App\Services\Api\EveOnline;

use App\Exceptions\EveApiException;
use App\Exceptions\EveApiTokenExpiredException;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Swagger\Client\Eve\ApiException;
use Swagger\Client\Eve\Model\GetCharactersCharacterIdLocationOk;

/**
 * Get current location of the character.
 */
class LocationApiRequest extends AbstractApiRequest
{
    /**
     * @param User $user
     * @throws EveApiTokenExpiredException
     * @throws EveApiException
     * @return array
     */
    public static function get(User $user, ?array $data = null): array
    {
        $api = self::getApiInstance('LocationApi', $user);

        self::log(
            'info',
            "User:{$user->characterId} getCharactersCharacterIdLocation"
        );

        try {
            $result = $api->getCharactersCharacterIdLocation(
                $user->characterId,
                static::$dataSource,
                '',
                $user->token
            );
        } catch (ApiException $e) {
            $errorResponse = self::getErrorResponse($e);

            if ($errorResponse['error'] == 'token is expired') {
                throw new EveApiTokenExpiredException('token is expired');
            }

            throw new EveApiException($e->getMessage(), $e->getCode());
        }

        if (!$result instanceof GetCharactersCharacterIdLocationOk) {
            throw new EveApiException('failed to get character location');
        }

        return self::toArray($result);
    }
}
