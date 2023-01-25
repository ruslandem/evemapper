<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SecuritySpace;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WormholeClass extends Model
{
    use HasFactory;

    protected $table = 'wormholeClasses';
    protected $primaryKey = 'id';

    protected $fillable = [];

    protected function inClass(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => SecuritySpace::tryFrom(intval($value))?->name()
        );
    }
}
