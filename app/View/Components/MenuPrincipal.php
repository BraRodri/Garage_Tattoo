<?php

namespace App\View\Components;

use App\Models\Categories;
use Illuminate\View\Component;

class MenuPrincipal extends Component
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
        $menus = Categories::where('active', 1)->get();
        return view('components.pagina.menu-principal', compact('menus'));
    }
}
