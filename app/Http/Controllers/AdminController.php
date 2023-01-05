<?php

namespace App\Http\Controllers;

use App\Core\EveSolarSystem;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('eve.auth:admin');
    }

    public function getStatistics()
    {
        return EveSolarSystem::getStatistics();
    }
}
