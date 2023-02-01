<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

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

    /**
     * Set the keys for a save update query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery($query)
    {
        $query
            ->where('characterId', '=', $this->getAttribute('characterId'))
            ->where('signatureId', '=', $this->getAttribute('signatureId'))
            ->where('solarSystemName', '=', $this->getAttribute('solarSystemName'));

        return $query;
    }
}
