<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LocationHistory extends Model
{
    use HasFactory;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = null;

    protected $table = 'locationHistory';

    public $timestamps = true;

    protected $fillable = [
        'userId',
        'solarSystemName',
        'solarSystemSecurity',
        'wormholeClass',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(
            User::class,
            'characterId',
            'userId'
        );
    }

    public function solarSystem(): HasOne
    {
        return $this->hasOne(
            SolarSystem::class,
            'solarSystemName',
            'solarSystemName'
        );
    }
}
