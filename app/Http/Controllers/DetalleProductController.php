<?php

namespace App\Http\Controllers;

use App\Models\Combinaciones;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductsGalleries;

class DetalleProductController extends Controller
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
        $product = Products::where('slug', $id)
            ->where('active', 1)
            ->firstOrFail();
        $product_id = $product->id;
        $category_id = $product->categories[0]->categories_id;

        $productFeatured = DB::select("SELECT *, products.title AS title_product,
        categories.title AS title_category, products.slug AS slug_producto, products.id AS product_id
        from products, categories, products_categories
        WHERE products_categories.categories_id = {$category_id} AND products_categories.products_id = products.id
        AND categories.id = products_categories.categories_id
        AND products.active = 1 AND products.id NOT IN (SELECT id FROM products WHERE id={$product_id})
        ORDER BY RAND() LIMIT 10");

        $images_product = array();
        foreach ($productFeatured as $key => $value) {
            $images_prod = ProductsGalleries::where('products_id', $value->product_id)->get();
            $images_product[] = $images_prod;
        }

        $combinaciones = Combinaciones::where('products_id', $product_id)->get();

        if ($product) {
            return view('pages.detalleProducto')
                ->with('product', $product)
                ->with('productFeatured', $productFeatured)
                ->with('images_product', $images_product)
                ->with('combinaciones', $combinaciones);
        } else {
            dd('No sirvio ' + $id);
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
