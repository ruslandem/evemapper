<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateLocationHistoryTable extends Migration
{
    protected $connection = 'app';

    protected $tableName = 'locationHistory';


    public function up()
    {
        // renaming table
        $tempTableName = $this->tableName . '_temp';
        Schema::dropIfExists($tempTableName);
        Schema::rename($this->tableName, $tempTableName);

        // creating new table
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->integer('userId');
            $table->string('solarSystemName');
            $table->float('solarSystemSecurity')->nullable();
            $table->string('wormholeClass')->nullable();
            $table->dateTime('createdAt');
        });

        // migrate data to a new table
        $conn = DB::connection($this->connection);
        $data = $conn->table($tempTableName)->get();
        foreach ($data as $item) {
            $conn->table($this->tableName)->insert([
                'id' => $item->id,
                'userId' => $item->userId,
                'solarSystemName' => $item->solarSystemName,
                'createdAt' => $item->createdAt
            ]);
        }

        // drop old table
        Schema::drop($tempTableName);
    }


    public function down()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->dropColumn(['solarSystemSecurity', 'wormholeClass']);
        });
    }
}
