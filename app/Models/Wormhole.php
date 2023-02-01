<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SecuritySpace;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Wormhole extends Model
{
    use HasFactory;

    protected $table = 'wormholeSystems';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [];
    protected $with = ['statics'];

    protected function class(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => SecuritySpace::tryFrom($value)?->name()
        );
    }

    public function solarSystem(): HasOne
    {
        return $this->hasOne(
            SolarSystem::class,
            'solarSystemID'
        );
    }

    public function statics(): HasManyThrough
    {
        return $this->hasManyThrough(
            WormholeClass::class,
            WormholeStatic::class,
            'wormholeID',
            'id',
            'id', 
            'staticID'
        );
    }
}
