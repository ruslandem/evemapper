<?php

namespace App\Core;

use DateTime;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;

class EveLocationHistory
{
    private ConnectionInterface $db;

    public function __construct(string $connection = 'app')
    {
        $this->db = DB::connection($connection);
    }

    /**
     * Records location (if changed) of the specified user into database.
     * 
     * @param int $userId
     * @param string $solarSystemName
     * @return bool
     */
    public function write(int $userId, string $solarSystemName): bool
    {
        // check previous location does not equals to present location
        $record = $this->db->table('locationHistory')
            ->where(['userId' => $userId])
            ->orderByDesc('createdAt')
            ->limit(1)
            ->first();

        if ($record && $record->solarSystemName == $solarSystemName) {
            return false;
        }

        $solarSystemInfo = (new EveSolarSystem())->getByName($solarSystemName);

        return $this->db->table('locationHistory')->insert([
            'userId' => $userId,
            'solarSystemName' => $solarSystemName,
            'solarSystemSecurity' => $solarSystemInfo->security,
            'wormholeClass' => $solarSystemInfo->wormholeClass ?? null,
            'createdAt' => new DateTime()
        ]);
    }

    public function get(int $userId, int $limit = 100)
    {
        return $this->db->table('locationHistory')
            ->where(['userId' => $userId])
            ->orderByDesc('createdAt')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}
