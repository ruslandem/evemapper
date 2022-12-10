<?php

namespace App\View\Components\Locator;

use Illuminate\View\Component;

class Hubs extends Component
{
    public $system;
    public $jumps;


    public function __construct($system, $jumps)
    {
        $this->system = $system;
        $this->jumps = $jumps;
    }

    public function render()
    {
        return view('components.locator.hubs');
    }
}
