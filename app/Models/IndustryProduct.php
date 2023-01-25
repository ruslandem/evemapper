<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class IndustryProduct extends Model
{
    use HasFactory;

    protected $table = 'industryActivityProducts';
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [];
    protected $with = ['productType'];

    public function productType(): HasOne
    {
        return $this->hasOne(
            InvType::class,
            'typeID',
            'productTypeID'
        );
    }
}
