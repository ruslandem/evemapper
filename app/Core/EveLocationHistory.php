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
        $prevRecord = DB::table('location_history')
            ->where(['userId' => $userId])
            ->orderByDesc('created_at')
            ->limit(1)
            ->first();

        if (
            $prevRecord &&
            isset($prevRecord->solarSystemName) &&
            $prevRecord->solarSystemName == $solarSystemName
        ) {
            return false;
        }

        return DB::table('location_history')->insert([
            'userId' => $userId,
            'solarSystemName' => $solarSystemName,
            'created_at' => new DateTime()
        ]);
    }

    public static function get(int $userId, int $limit = 100)
    {
        $records = DB::table('location_history')
            ->where(['userId' => $userId])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get(['solarSystemName', 'created_at'])
            ->toArray();

        return $records;
    }
}
