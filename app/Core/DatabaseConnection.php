<?php

namespace App\Core;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;

class DatabaseConnection
{
    public static string $connectionConfig = 'app';

    protected static function db(): ConnectionInterface
    {
        return DB::connection(static::$connectionConfig);
    }
}
