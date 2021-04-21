<?php

namespace App\View\Components;

use App\Models\Modals;
use Illuminate\View\Component;

class ModalAvisos extends Component
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
        $info = Modals::all();
        return view('components.pagina.modal-avisos', compact('info', $info));
    }
}
