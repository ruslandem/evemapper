<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'app';

    protected $fillable = [
        'characterId',
        'characterName',
        'ownerHash',
        'token',
        'refreshToken',
        'scopes',
        'rememberToken',
    ];

    protected $primaryKey = 'characterId';

    protected $rememberTokenName = 'rememberToken';

    protected $casts = [
        'scopes' => 'array',
    ];

    public function locationHistory(): HasMany
    {
        return $this->hasMany(
            LocationHistory::class,
            'userId',
            'characterId'
        );
    }
}
