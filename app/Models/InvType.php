<?php

namespace App\Models;

use App\Traits\HasModelCachableAttributes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvType extends Model
{
    use HasFactory, HasModelCachableAttributes;
    /**
     * Value of activityID for manufacturing activities.
     */
    public const MANUFACTURE_ACTIVITY = 1;

    protected $connection = 'app';

    protected $table = 'invTypes';

    protected $fillable = [];

    protected $primaryKey = 'typeID';


    protected function materials(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->useAttributeCache('materials', function () {
                    return $this->getConnection()
                        ->table('industryActivityMaterials')
                        ->where([
                            'typeID' => $this->typeID,
                            'activityID' => self::MANUFACTURE_ACTIVITY
                        ])
                        ->get(['materialTypeID', 'quantity']);
                });
            }
        );
    }
}
