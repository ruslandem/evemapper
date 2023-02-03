<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalLink extends Model
{
    use HasFactory;

    protected $table = 'extLinks';
    protected $primaryKey = 'name';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $appends = ['wiki_url'];

    protected $fillable = [
        'name',
        'url',
    ];


    protected function wikiUrl(): Attribute
    {
        return new Attribute(
            get: fn () => implode('', [
                'https://wiki.eveuniversity.org/',
                urlencode(
                    str_replace(" ", "_", trim($this->name))
                )
            ])
        );
    }
}
