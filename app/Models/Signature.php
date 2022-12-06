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
