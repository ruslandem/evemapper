<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMapSolarSystemsTable extends Migration
{
    protected $connection = 'app';

    protected $tableName = 'mapSolarSystems';


    public function up()
    {
        // if (app()->environment('testing')) {
        //     return;
        // }

        Schema::dropIfExists($this->tableName);
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->integer('regionID');
            $table->integer('constellationID');
            $table->integer('solarSystemID')->primary();
            $table->string('solarSystemName', 100);
            $table->float('x');
            $table->float('y');
            $table->float('z');
            $table->float('xMin');
            $table->float('xMax');
            $table->float('yMin');
            $table->float('yMax');
            $table->float('zMin');
            $table->float('zMax');
            $table->float('luminosity');
            $table->boolean('border');
            $table->boolean('fringe');
            $table->boolean('corridor');
            $table->boolean('hub');
            $table->boolean('international');
            $table->boolean('regional');
            $table->boolean('constellation')->nullable();
            $table->float('security');
            $table->integer('factionID')->nullable();
            $table->float('raduis')->nullable();
            $table->integer('sunTypeID')->nullable();
            $table->string('securityClass', 2)->nullable();
        });

        DB::connection($this->connection)->unprepared(
            file_get_contents('./database/init/mapSolarSystems.sql')
        );
    }

    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
