<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateWormholeSystemsTable extends Migration
{
    protected $connection = 'app';

    protected $tableName = 'wormholeSystems';


    public function up()
    {
        Schema::dropIfExists($this->tableName);
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->string('system');
            $table->integer('class');
            $table->string('star');
            $table->integer('planets');
            $table->integer('moons');
            $table->string('effect')->nullable();
            $table->string('static')->nullable();
        });

        DB::connection($this->connection)->unprepared(
            file_get_contents('database/init/wormholeSystems.sql')
        );
    }

    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
