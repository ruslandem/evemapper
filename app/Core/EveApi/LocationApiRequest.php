<?php

declare(strict_types=1);

namespace App\Core\EveApi;

use App\Core\Exceptions\EveApiException;
use App\Core\Exceptions\EveApiTokenExpiredException;
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

        if (Config::get('app.api.log', true)) {
            Log::channel('api')->info(
                "User:{$user->characterId} getCharactersCharacterIdLocation"
            );
        }

        try {
            $result = $api->getCharactersCharacterIdLocation(
                $user->characterId,
                static::$dataSource,
                '',
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

        if (!$result instanceof GetCharactersCharacterIdLocationOk) {
            throw new EveApiException('failed to get character location');
        }

        return self::toArray($result);
    }
}
