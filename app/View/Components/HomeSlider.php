<?php

namespace App\View\Components;

use App\Models\Sliders;
use Illuminate\View\Component;

class HomeSlider extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $slider_home = Sliders::all();
        return view('components.pagina.home-slider', compact('slider_home', $slider_home));
    }
}
