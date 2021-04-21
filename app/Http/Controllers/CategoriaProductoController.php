<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\ProductsCategories;
use Illuminate\Http\Request;

class CategoriaProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $categoria = Categories::where('slug', $id)
            ->where('active', 1)
            ->firstOrFail();
        $fullCategorias = Categories::all();

        if ($categoria) {

            $PaginaTitulo = $categoria->title;
            $IdCategoria = $categoria->id;

            $productsCategoria = ProductsCategories::where('categories_id', $IdCategoria)->orderBy('created_at', 'ASC')->paginate(12);
            $productsCategoriaN = ProductsCategories::where('categories_id', $IdCategoria)->count();

            return view('pages.categoriaProducto')
                ->with('PaginaTitulo', $PaginaTitulo)
                ->with('productsCategoria', $productsCategoria)
                ->with('productsCategoriaN', $productsCategoriaN)
                ->with('categoria', $categoria)
                ->with('fullCategorias', $fullCategorias);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
