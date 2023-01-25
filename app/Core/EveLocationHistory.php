<?php

namespace App\Core;

use DateTime;
use Illuminate\Support\Facades\DB;

class EveLocationHistory
{
    /**
     * Records location (if changed) of the specified user into database.
     * 
     * @param int $userId
     * @param string $solarSystemName
     * @return bool
     */
    public static function write(int $userId, string $solarSystemName): bool
    {
        // check previous location does not equals to present location
        $record = DB::table('locationHistory')
            ->where(['userId' => $userId])
            ->orderByDesc('createdAt')
            ->limit(1)
            ->first();

        if ($record && $record->solarSystemName == $solarSystemName) {
            return false;
        }

        $solarSystemInfo = EveSolarSystem::getByName($solarSystemName);

        return DB::table('locationHistory')->insert([
            'userId' => $userId,
            'solarSystemName' => $solarSystemName,
            'solarSystemSecurity' => $solarSystemInfo->security,
            'wormholeClass' => $solarSystemInfo->wormholeClass ?? null,
            'createdAt' => new DateTime()
        ]);
    }

    public static function get(int $userId, int $limit = 100)
    {
        return DB::table('locationHistory')
            ->where(['userId' => $userId])
            ->orderByDesc('createdAt')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}
