<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SolarSystemJumps extends Model
{
    use HasFactory;

    protected $table = 'mapSolarSystemJumps';
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [];
    protected $with = ['toSolarSystem' => ['region']];

    public function fromRegion(): HasOne
    {
        return $this->hasOne(
            Region::class,
            'regionID',
            'fromRegionID',
        );
    }

    public function fromConstellation(): HasOne
    {
        return $this->hasOne(
            Constellation::class,
            'constellationID',
            'fromConstellationID',
        );
    }

    public function fromSolarSystem(): HasOne
    {
        return $this->hasOne(
            SolarSystem::class,
            'solarSystemID',
            'fromSolarSystemID',
        );
    }

    public function toRegion(): HasOne
    {
        return $this->hasOne(
            Region::class,
            'regionID',
            'toRegionID',
        );
    }

    public function toConstellation(): HasOne
    {
        return $this->hasOne(
            Constellation::class,
            'constellationID',
            'toConstellationID',
        );
    }

    public function toSolarSystem(): HasOne
    {
        return $this->hasOne(
            SolarSystem::class,
            'solarSystemID',
            'toSolarSystemID',
        );
    }
}

// fromRegionID: 10000001,
// fromConstellationID: 20000002,
// fromSolarSystemID: 30000009,
// toSolarSystemID: 30000010,
// toConstellationID: 20000002,
// toRegionID: 10000001,