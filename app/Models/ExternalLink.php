<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalLink extends Model
{
    use HasFactory;

    protected $table = 'extLinks';
    protected $primaryKey = 'name';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'url',
    ];


    public function getWikiUrl()
    {
        return "https://wiki.eveuniversity.org/" 
            . urlencode(str_replace(" ", "_", trim($this->name)));
    }
}
