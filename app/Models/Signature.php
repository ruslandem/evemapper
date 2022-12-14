<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    use HasFactory;

    protected $connection = 'app';

    protected $table = 'signatures';

    protected $fillable = [
        'characterId',
        'solarSystemName',
        'signatureId',
        'signatureName',
        'groupName',
        'created_at',
        'updated_at'
    ];

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