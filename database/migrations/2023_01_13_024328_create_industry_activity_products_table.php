<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateIndustryActivityProductsTable extends Migration
{
    protected $connection = 'app';

    protected $tableName = 'industryActivityProducts';


    public function up()
    {
        Schema::dropIfExists($this->tableName);
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->integer('typeID');
            $table->integer('activityID');
            $table->integer('productTypeID');
            $table->integer('quantity');
            $table->index(['typeID', 'activityID']);
        });

        DB::connection($this->connection)->unprepared(
            file_get_contents('./database/init/industryActivityProducts.sql')
        );
    }

    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
