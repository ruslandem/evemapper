<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegionRats extends Model
{
    use HasFactory;

    protected $table = 'regionRats';
    protected $primaryKey = 'regionName';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [];

    public function region(): BelongsTo
    {
        return $this->belongsTo(
            Region::class,
            'regionName'
        );
    }
}
