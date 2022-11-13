<?php 

namespace App\Http\Controllers;

use App\Core\EveRoute;
use Illuminate\Support\Facades\DB;

class RoutesController extends Controller
{
    public function route()
    {
        $eveRoute = new EveRoute(
            DB::connection('sqlite')
        );

        $route = $eveRoute->getRoute('Jita', 'Amarr');

        dd($route);
    }
}