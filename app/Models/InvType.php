<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InvType extends Model
{
    use HasFactory;

    protected $connection = 'app';

    protected $table = 'invTypes';

    protected $fillable = [];

    protected $primaryKey = 'typeID';


    protected function materials(): Attribute
    {
        return Attribute::make(
            get: fn () =>
            DB::connection('app')->table('industryActivityMaterials')
                ->where(['typeID' => $this->typeID, 'activityID' => 1])
                ->get(['materialTypeID', 'quantity'])
        );
    }
}
