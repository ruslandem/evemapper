<?php

namespace App\View\Components\Locator;

use Illuminate\View\Component;

class Hubs extends Component
{
    public $system;
    
    public $jumps;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($jumps)
    {
        $this->jumps = $jumps;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.locator.hubs');
    }
}
