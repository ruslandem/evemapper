<?php

namespace App\Http\Controllers;

use App\Core\EveApi;
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
                'errorMessage' => 'Not system specified'
            ]);
        }

        $systems = DB::table('wormhole_systems')->where('system', '=', $system)->get();

        try {
            $found = $systems->firstOrFail();
        } catch (ItemNotFoundException $e) {
            return view('system', [
                'system' => null,
                'errorMessage' => 'Not found'
            ]);
        }

        $statics = array_flip(
            explode(",", $found->static)
        );

        foreach ($statics as $staticName => $data) {
            $classes = DB::table('wormhole_classes')->where('hole', '=', $staticName)->get();
            $statics[$staticName] = $classes->first();
        }

        $found->static = $statics;

        return view('system', [
            'system' => $found
        ]);
    }
}
