<?php

namespace App\View\Components;

use App\Models\Categories;
use App\Models\Configurations;
use App\Models\Publicities;
use Illuminate\View\Component;

class Header extends Component
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
        $info = Configurations::findOrFail(1);
        $menus = Categories::where('active', 1)->where('level', 1)->orderBy('position', 'ASC')->get();

        $alerta_promociones = Publicities::where('active', 1)->get();
        return view('components.header', compact('info', 'menus', 'alerta_promociones'));
    }
}
