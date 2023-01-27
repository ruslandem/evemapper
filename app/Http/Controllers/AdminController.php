<?php

namespace App\Http\Controllers;

use App\Services\Admin\AdminSolarSystems;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('eve.auth:admin');
    }

    public function getStatistics()
    {
        return AdminSolarSystems::getStatistics();
    }
}
