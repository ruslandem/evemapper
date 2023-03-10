<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WormholeStatic extends Model
{
    use HasFactory;

    protected $table = 'wormholeStatics';
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [];
}
