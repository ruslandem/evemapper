<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateInvTypesTable extends Migration
{
    protected $connection = 'app';

    protected $tableName = 'invTypes';


    public function up()
    {
        Schema::dropIfExists($this->tableName);
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->integer('typeID');
            $table->integer('groupID');
            $table->string('typeName');
            $table->float('mass');
            $table->float('volume');
            $table->float('capacity');
            $table->integer('basePrice')->nullable();
            $table->primary(['typeID']);
            $table->index(['typeName']);
        });

        DB::connection($this->connection)->unprepared(
            file_get_contents('./database/init/invTypes.sql')
        );
    }

    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
