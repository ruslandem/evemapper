<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteColumnStaticFromWormholeSystemsTable extends Migration
{
    protected $connection = 'app';
    protected $tableName = 'wormholeSystems';


    public function up()
    {
        Schema::dropColumns($this->tableName, ['static']);
    }

    public function down()
    {
        //
    }
};
