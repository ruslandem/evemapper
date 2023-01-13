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
                        ->table('industryActivityMaterials AS m')
                        ->leftJoin('invTypes AS i', 'i.typeID', '=', 'm.materialTypeID')
                        ->where([
                            'm.typeID' => $this->typeID,
                            'm.activityID' => self::MANUFACTURE_ACTIVITY
                        ])
                        ->get([
                            'm.materialTypeID AS typeId',
                            'i.typeName AS name',
                            'm.quantity'
                        ])
                        ->toArray();
                });
            }
        );
    }

    protected function products(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->useAttributeCache('products', function () {
                    return $this->getConnection()
                        ->table('industryActivityProducts AS p')
                        ->leftJoin('invTypes AS i', 'i.typeID', '=', 'p.productTypeID')
                        ->where([
                            'p.typeID' => $this->typeID,
                            'p.activityID' => self::MANUFACTURE_ACTIVITY
                        ])
                        ->get([
                            'p.productTypeID AS typeId',
                            'i.typeName AS name',
                            'p.quantity'
                        ])
                        ->toArray();
                });
            }
        );
    }
}
