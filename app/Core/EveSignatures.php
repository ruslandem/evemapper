<?php

namespace App\Core;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EveSignatures
{
    protected ConnectionInterface $db;

    public function __construct(string $connection = 'app')
    {
        $this->db = DB::connection($connection);
    }

    /**
     * Get signatures for specified user and solar system.
     * 
     * @param int $userId
     * @param string $solarSystemName
     * @return array
     */
    public function get(int $userId, string $solarSystemName): array
    {
        return $this->db->table('signatures')
            ->where('characterId', '=', $userId)
            ->where('solarSystemName', '=', $solarSystemName)
            ->orderBy('signatureId')
            ->get()
            ->toArray();
    }

    /**
     * Update signatures for the specified user and solar system (handle clipboard text).
     * 
     * @param int $userId
     * @param string $solarSystemName
     * @param string $string
     * @return array
     */
    public function updateFromString(int $userId, string $solarSystemName, string $string): array
    {
        $signatures = [];

        $lines = explode(PHP_EOL, $string);
        foreach ($lines as $line) {
            list($id,, $group, $name) = str_getcsv(trim($line), "\t");

            if ($id) {
                $signatures[] = [
                    'characterId' => $userId,
                    'solarSystemName' => $solarSystemName,
                    'signatureId' => $id,
                    'signatureName' => $name,
                    'groupName' => $group ?? '',
                ];
            }
        }

        return $this->update($userId, $solarSystemName, $signatures);
    }

    /**
     * Update signatures for the specified user and solar system.
     * 
     * @param int $userId
     * @param string $solarSystemName
     * @param array $signatures
     * @return array
     */
    public function update(int $userId, string $solarSystemName, array $signatures): array
    {
        foreach ($signatures as $signature) {
            $attributes = [
                'characterId' => $userId,
                'solarSystemName' => $solarSystemName,
                'signatureId' => $signature['signatureId']
            ];

            $record = array_merge($attributes, [
                'signatureName' => $signature['signatureName'],
                'groupName' => $signature['groupName'],
                'updated_at' => now()
            ]);

            $existing = $this->db->table('signatures')
                ->where($attributes)
                ->get()
                ->first();

            if ($existing) {
                if (!empty($record['signatureName']) || !empty($record['groupName'])) {
                    $this->db->table('signatures')
                        ->where($attributes)
                        ->update($record);
                }
            } else {
                $record['created_at'] = now();
                $this->db->table('signatures')
                    ->insert($record);
            }
        }

        return $this->get($userId, $solarSystemName);
    }

    /**
     * Deleting a signature for the specified user and solar system.
     * 
     * @param int $userId
     * @param string $solarSystemName
     * @param string $signatureId
     * @return int
     */
    public function delete(int $userId, string $solarSystemName, string $signatureId): int
    {
        return $this->db->table('signatures')
            ->where('characterId', '=', $userId)
            ->where('solarSystemName', '=', $solarSystemName)
            ->where('signatureId', '=', $signatureId)
            ->delete();
    }

    /**
     * Deleting all signatures for the specified user and solar system.
     * 
     * @param int $userId
     * @param string $solarSystemName
     * @return int
     */
    public function deleteAll(int $userId, string $solarSystemName): int
    {
        return $this->db->table('signatures')
            ->where('characterId', '=', $userId)
            ->where('solarSystemName', '=', $solarSystemName)
            ->delete();
    }

    /**
     * Clear signatures table by deleting all expired records.
     * 
     * @param int $hours
     * @return int
     */
    public function deleteExpired(int $hours = 24): int
    {
        return $this->db->table('signatures')
            ->whereDate('created_at', '<=', now()->subHours($hours))
            ->delete();
    }
}
