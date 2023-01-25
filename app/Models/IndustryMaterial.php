<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class IndustryMaterial extends Model
{
    use HasFactory;

    protected $table = 'industryActivityMaterials';
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [];

    protected $with = ['materialType'];

    public function materialType(): HasOne
    {
        return $this->hasOne(
            InvType::class,
            'typeID',
            'materialTypeID'
        );
    }
}
