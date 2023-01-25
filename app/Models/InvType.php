<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasModelCachableAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvType extends Model
{
    use HasFactory, HasModelCachableAttributes;

    public const ACTIVITY_MANUFACTURE = 1;

    protected $table = 'invTypes';
    protected $primaryKey = 'typeID';
    public $incrementing = false;

    protected $fillable = [];

    protected $with = [];

    public function materials(): HasMany
    {
        return $this->hasMany(
            IndustryMaterial::class,
            'typeID',
            'typeID'
        )->where(['activityID' => self::ACTIVITY_MANUFACTURE]);
    }

    public function products(): HasMany
    {
        return $this->hasMany(
            IndustryProduct::class,
            'typeID',
            'typeID'
        )->where(['activityID' => self::ACTIVITY_MANUFACTURE]);
    }
}
