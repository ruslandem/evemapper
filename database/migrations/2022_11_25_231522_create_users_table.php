<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    protected $connection = 'app';

    protected $tableName = 'users';


    public function up()
    {
        Schema::dropIfExists($this->tableName);
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->integer('characterId', false, true)->primary();
            $table->string('characterName');
            $table->string('ownerHash');
            $table->string('token');
            $table->string('refreshToken');
            $table->string('rememberToken')->nullable();
            $table->text('scopes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
