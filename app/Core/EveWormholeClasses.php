<?php

namespace App\Core;

class EveWormholeClasses extends DatabaseConnection
{
    public static function getList(int $wormholeClass): array
    {
        $classes = self::db()->table('wormholeClasses')
            ->where('inClass', '=', $wormholeClass)
            ->orderBy('hole')
            ->get();
        
        return array_column($classes->toArray(), 'hole');
    }
}
