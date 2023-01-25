<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SolarSystem extends Model
{
    use HasFactory;

    protected $table = 'mapSolarSystems';
    protected $primaryKey = 'solarSystemID';
    public $incrementing = false;

    protected $fillable = [];
    protected $with = ['wormhole'];

    public function constellation(): BelongsTo
    {
        return $this->belongsTo(
            Constellation::class,
            'constellationID'
        );
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(
            Region::class,
            'regionID'
        );
    }

    public function wormhole(): HasOne
    {
        return $this->hasOne(
            Wormhole::class,
            'id'
        );
    }

    public function jumps(): HasMany
    {
        return $this->hasMany(
            SolarSystemJumps::class,
            'fromSolarSystemID',
            'solarSystemID'
        );
    }
}
