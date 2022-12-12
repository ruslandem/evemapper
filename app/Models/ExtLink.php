<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtLink extends Model
{
    use HasFactory;

    protected $connection = 'app';

    protected $table = 'extLinks';

    protected $fillable = [
        'name',
        'url',
    ];

    protected $primaryKey = 'name';

    public function getWikiUrl()
    {
        return "https://wiki.eveuniversity.org/" 
            . urlencode(str_replace(" ", "_", trim($this->name)));
    }
}
