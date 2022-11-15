<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationHistoryTable extends Migration
{
    protected $connection = 'app';

    protected $tableName = 'locationHistory';


    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->integer('userId');
            $table->string('solarSystemName');
            $table->dateTime('createdAt');
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
