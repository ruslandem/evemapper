<?php

namespace App\Http\Controllers;

use App\Core\EveApiRoute;
use App\Core\EveAuth;
use App\Core\EveRoute;
use Illuminate\Support\Facades\DB;

class RoutesController extends Controller
{
    public function route()
    {
        $origin = 'Jita';
        $destination = 'Amarr';

        $eveRoute = new EveRoute();
        dd($eveRoute->getRoute($origin, $destination));
    }
}
