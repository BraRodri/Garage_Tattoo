<?php

namespace App\View\Components;

use App\Models\Products;
use Illuminate\View\Component;

class ProductosOfertas extends Component
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
        $ofertas = Products::where('offer', 1)
            ->where('active', 1)
            ->orderBy('created_at', 'DESC')
            ->get();
        return view('components.pagina.productos-ofertas', compact('ofertas'));
    }
}
