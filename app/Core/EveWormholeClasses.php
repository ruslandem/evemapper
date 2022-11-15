<?php

namespace App\Core;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;

class EveWormholeClasses
{
    private ConnectionInterface $db;

    public function __construct(string $connection = 'app') {
        $this->db = DB::connection($connection);
    }

    public function getList(int $wormholeClass): array
    {
        $classes = $this->db->table('wormholeClasses')
            ->where('inClass', '=', $wormholeClass)
            ->orderBy('hole')
            ->get();
        
        return array_column($classes->toArray(), 'hole');
    }
}
