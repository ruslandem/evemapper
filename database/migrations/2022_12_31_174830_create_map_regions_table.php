<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMapRegionsTable extends Migration
{
    protected $connection = 'app';

    protected $tableName = 'mapRegions';


    public function up()
    {
        if (app()->environment('testing')) {
            return;
        }
        
        Schema::dropIfExists($this->tableName);
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->integer('regionID')->primary();
            $table->string('regionName', 100);
            $table->float('x');
            $table->float('y');
            $table->float('z');
            $table->float('xMin');
            $table->float('xMax');
            $table->float('yMin');
            $table->float('yMax');
            $table->float('zMin');
            $table->float('zMax');
            $table->integer('factionID')->nullable();
            $table->integer('nebula')->nullable();
            $table->float('radius')->nullable();
        });

        DB::connection($this->connection)->unprepared(
            file_get_contents('./database/init/mapRegions.sql')
        );
    }

    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
