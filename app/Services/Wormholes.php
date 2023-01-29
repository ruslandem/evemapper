<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\SecuritySpace;
use App\Models\Wormhole;
use App\Models\WormholeClass;
use Illuminate\Support\Collection;

class Wormholes
{
    /**
     * Get wormhole statics for the specified wormhole system
     * 
     * @param Wormhole $wormhole
     * @return Collection
     */
    public static function getWormholeStatics(Wormhole $wormhole): Collection
    {
        $result = new Collection();

        if ($wormhole->static) {
            foreach (explode(',', $wormhole->static) as $staticName) {
                $result->push(
                    WormholeClass::where(['hole' => $staticName])->first()
                );
            }
        }

        return $result;
    }

    /**
     * Return simple array of wormhole classes with specified security space
     * 
     * @param SecuritySpace $securitySpace
     * @return array
     */
    public static function getWormholeClasses(SecuritySpace $securitySpace): array
    {
        return array_column(
            WormholeClass::where(['inClass' => $securitySpace])
                ->orderBy('hole')
                ->get()
                ->toArray(),
            'hole'
        );
    }
}
