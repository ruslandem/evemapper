<?php

namespace App\Http\Controllers;

use App\Core\EveSolarSystem;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.eveonline:admin');
    }

    public function getStatistics()
    {
        return (new EveSolarSystem())->getStatistics();
    }
}
