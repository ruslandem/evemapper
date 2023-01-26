<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameConstellationColumnInMapSolarSystemsTable extends Migration
{
    public function up()
    {
        Schema::table('mapSolarSystems', function (Blueprint $table) {
            $table->renameColumn('constellation', 'isConstellation');
        });
    }

    public function down()
    {
        Schema::table('mapSolarSystems', function (Blueprint $table) {
            $table->renameColumn('isConstellation', 'constellation');
        });
    }
};
