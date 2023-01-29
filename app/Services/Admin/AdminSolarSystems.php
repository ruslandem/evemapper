<?php

declare(strict_types=1);

namespace App\Services\Admin;

use Illuminate\Support\Facades\DB;

class AdminSolarSystems
{
    public static function getStatistics(): array
    {
        $data = DB::table('signatures')
            ->select(
                'signatures.characterId',
                'users.characterName',
                DB::raw('COUNT(DISTINCT signatures.solarSystemName) as solarSystemsCount'),
                DB::raw('COUNT(DISTINCT signatures.signatureId) as signaturesCount'),
                DB::raw('MAX(signatures.updated_at) as updatedAt')
            )
            ->leftJoin('users', 'users.characterId', '=', 'signatures.characterId')
            ->groupBy(['signatures.characterId'])
            ->get();

        return $data->toArray();
    }
}
