<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWormholeStaticsTable extends Migration
{
    protected $connection = 'app';
    protected $tableName = 'wormholeStatics';


    public function up()
    {
        Schema::dropIfExists($this->tableName);

        Schema::create($this->tableName, function (Blueprint $table) {
            $table->integer('wormholeID');
            $table->integer('staticID');
            $table->index(['wormholeID']);
        });

        DB::connection($this->connection)->unprepared(
            file_get_contents('./database/init/wormholeStatics.sql')
        );
    }

    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
};
