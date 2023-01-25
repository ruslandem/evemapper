<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CosmicSignature extends Model
{
    use HasFactory;

    protected $table = 'signatures';
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'characterId',
        'solarSystemName',
        'signatureId',
        'signatureName',
        'groupName',
        'created_at',
        'updated_at'
    ];

    public function solarSystem(): BelongsTo
    {
        return $this->belongsTo(
            SolarSystem::class,
            'solarSystemName',
            'solarSystemName',
        );
    }

    /**
     * Updates signature only if the new data contains information.
     * 
     * @param array $attributes
     * @return $this
     */
    public function updateData(array $attributes)
    {
        foreach (['signatureName', 'groupName'] as $value) {
            if (!empty($attributes[$value])) {
                $this->attributes[$value] = $attributes[$value];
            }
        }

        return $this;
    }
}
