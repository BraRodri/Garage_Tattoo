<?php

namespace App\View\Components;

use App\Models\SlidersClients;
use Illuminate\View\Component;

class SliderMarca extends Component
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
        $clientes = SlidersClients::all();
        return view('components.pagina.slider-marca', compact('clientes', $clientes));
    }
}
