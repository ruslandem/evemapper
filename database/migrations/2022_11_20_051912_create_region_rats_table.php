<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRegionRatsTable extends Migration
{
    protected $connection = 'app';

    protected $tableName = 'regionRats';


    public function up()
    {       
        Schema::dropIfExists($this->tableName);
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->string('regionName');
            $table->string('rats')->nullable();
        });

        DB::connection($this->connection)->unprepared(
            file_get_contents('database/init/regionRats.sql')
        );
    }

    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
