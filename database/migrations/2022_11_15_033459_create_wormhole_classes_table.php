<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateWormholeClassesTable extends Migration
{
    protected $connection = 'app';

    protected $tableName = 'wormholeClasses';


    public function up()
    {
        if (app()->environment('testing')) {
            return;
        }

        Schema::dropIfExists($this->tableName);
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->string('hole');
            $table->string('inClass');
            $table->bigInteger('maxStableTime');
            $table->bigInteger('maxStableMass');
            $table->bigInteger('massRegeneration');
            $table->bigInteger('maxJumpMass');
        });

        DB::connection($this->connection)->unprepared(
            file_get_contents('database/init/wormholeClasses.sql')
        );
    }

    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
