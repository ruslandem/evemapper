<?php

namespace App\Http\Controllers;

use App\Core\EveApi;
use App\Core\EveSolarSystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ItemNotFoundException;

class WormholesController extends Controller
{
    public function show($system = null)
    {
        if ($system === null) {
            return view('system', [
                'system' => null,
                'errorMessage' => 'System not specified'
            ]);
        }

        $solarSystem = new EveSolarSystem(0, $system);
        $found = $solarSystem->getData();

        return view('system', [
            'system' => $found
        ]);
    }
}
