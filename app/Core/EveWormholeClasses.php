<?php

namespace App\Core;

use Illuminate\Support\Facades\DB;

class EveWormholeClasses
{
    public static function getList(int $wormholeClass): array
    {
        $classes = DB::table('wormholeClasses')
            ->where('inClass', '=', $wormholeClass)
            ->orderBy('hole')
            ->get();
        
        return array_column($classes->toArray(), 'hole');
    }
}
