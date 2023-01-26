<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Singleton;
use App\Models\LocationHistory;
use App\Models\SolarSystem;
use App\Models\User;

class UserLocationHistory extends Singleton
{
    public static function getHistory(User $user, int $count = 50): ?array
    {
        return LocationHistory::where(['userId' => $user->characterId])
            ->orderBy('createdAt', 'desc')
            ->limit($count)
            ->get()
            ->toArray();
    }

    public static function addHistory(User $user, SolarSystem $solarSystem): ?LocationHistory
    {
        $history = UserLocationHistory::getHistory($user, 1);

        if (
            $history &&
            $history[0]['solarSystemName'] == $solarSystem->solarSystemName
        ) {
            // skip adding system to history
            // because the user is in the same system
            return null;
        }

        return LocationHistory::create([
            'userId' => $user->characterId,
            'solarSystemName' => $solarSystem->solarSystemName,
            'solarSystemSecurity' => $solarSystem->security,
            'wormholeClass' => $solarSystem->wormhole?->class()
        ]);
    }
}
