<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Constellation extends Model
{
    use HasFactory;

    protected $table = 'mapConstellations';
    protected $primaryKey = 'constellationID';
    public $incrementing = false;

    protected $fillable = [];

    public function solarSystems(): HasMany
    {
        return $this->hasMany(
            SolarSystem::class,
            'constellationID',
        );
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(
            Region::class,
            'regionID'
        );
    }
}
