<?php

namespace App\View\Components;

use App\Models\Blog;
use Illuminate\View\Component;

class MenuIzqBlog extends Component
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
        $blog = Blog::where('active', 1)
            ->orderBy('created_at', 'DESC')
            ->get();
        return view('components.pagina.menu-izq-blog', compact('blog'));
    }
}
