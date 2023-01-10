<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMapSolarSystemJumpsTable extends Migration
{
    protected $connection = 'app';

    protected $tableName = 'mapSolarSystemJumps';


    public function up()
    {
        Schema::dropIfExists($this->tableName);
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->integer('fromRegionID')->nullable();
            $table->integer('fromConstellationID')->nullable();
            $table->integer('fromSolarSystemID');
            $table->integer('toSolarSystemID');
            $table->integer('toConstellationID')->nullable();
            $table->integer('toRegionID')->nullable();
            $table->primary(['fromSolarSystemID', 'toSolarSystemID']);
        });

        DB::connection($this->connection)->unprepared(
            file_get_contents('./database/init/mapSolarSystemJumps.sql')
        );
    }

    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
