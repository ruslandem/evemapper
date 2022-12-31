<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class CreateExtLinksTable extends Migration
{
    protected $connection = 'app';

    protected $tableName = 'extLinks';


    public function up()
    {
        if (app()->environment('testing')) {
            return;
        }
        
        Schema::dropIfExists($this->tableName);
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->string('name')->primary();
            $table->string('url')->nullable();
        });

        DB::connection($this->connection)->unprepared(
            file_get_contents('database/init/extLinks.sql')
        );
    }

    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
