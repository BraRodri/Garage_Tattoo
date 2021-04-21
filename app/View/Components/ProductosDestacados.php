<?php

namespace App\View\Components;

use App\Models\Products;
use Illuminate\View\Component;

class ProductosDestacados extends Component
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
        $destacados = Products::where('featured', 1)
            ->where('active', 1)
            ->orderBy('created_at', 'DESC')
            ->limit(8)
            ->get();
        return view('components.pagina.productos-destacados', compact('destacados'));
    }
}
