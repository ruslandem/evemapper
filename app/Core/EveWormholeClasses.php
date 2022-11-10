<?php

namespace App\Core;

use Illuminate\Support\Facades\DB;

class EveWormholeClasses
{
    public function getList(int $wormholeClass): array
    {
        $classes = DB::table('wormhole_classes')
            ->where('in_class', '=', $wormholeClass)
            ->orderBy('hole')
            ->get();
        
        return array_column($classes->toArray(), 'hole');
    }
}
