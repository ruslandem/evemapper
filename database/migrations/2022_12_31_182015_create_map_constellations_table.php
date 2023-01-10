<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMapConstellationsTable extends Migration
{
    protected $connection = 'app';

    protected $tableName = 'mapConstellations';


    public function up()
    {       
        Schema::dropIfExists($this->tableName);
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->integer('regionID');
            $table->integer('constellationID')->primary();
            $table->string('constellationName', 100);
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
            $table->float('radius')->nullable();
        });

        DB::connection($this->connection)->unprepared(
            file_get_contents('./database/init/mapConstellations.sql')
        );
    }

    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
