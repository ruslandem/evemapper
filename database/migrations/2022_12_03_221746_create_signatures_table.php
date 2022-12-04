<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateSignaturesTable extends Migration
{
    protected $connection = 'app';

    protected $tableName = 'signatures';

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->integer('characterId', false, true);
            $table->string('solarSystemName');
            $table->string('signatureId');
            $table->string('signatureName')->nullable();
            $table->string('groupName')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
