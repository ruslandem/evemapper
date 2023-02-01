<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Region extends Model
{
    use HasFactory;

    protected $table = 'mapRegions';
    protected $primaryKey = 'regionID';
    public $incrementing = false;

    protected $fillable = [];
    protected $with = ['rats'];

    public function constellations(): HasMany
    {
        return $this->hasMany(
            Constellation::class,
            'regionID'
        );
    }

    public function solarSystems(): HasMany
    {
        return $this->hasMany(
            SolarSystem::class,
            'regionID'
        );
    }

    public function rats(): HasOne
    {
        return $this->hasOne(
            RegionRats::class,
            'regionName',
            'regionName'
        );
    }
}
