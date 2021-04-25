<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Blog;
use App\Models\Faqs;
use App\Models\Pages;
use App\Models\Clients;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Models\Configurations;
use App\Models\SlidersPartners;
use App\Models\ProductsGalleries;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $info = Configurations::findOrFail(1);
        return view('home')->with('PaginaTitulo', "Home")
            ->with('info', $info);
    }

    public function nosotros()
    {
        $PaginaTitulo = "Nosotros";
        $info = Pages::findOrFail(1);
        return view('pages.nosotros', compact('PaginaTitulo', 'info'));
    }

    public function pagoSeguro()
    {
        $PaginaTitulo = "Pago Seguro";
        $info = Pages::findOrFail(2);
        return view('pages.pagoSeguro', compact('PaginaTitulo', 'info'));
    }

    public function politicaEnvios()
    {
        $PaginaTitulo = "Política de Envíos";
        $info = Pages::findOrFail(3);
        return view('pages.politicaEnvios', compact('PaginaTitulo', 'info'));
    }

    public function devoluciones()
    {
        $PaginaTitulo = "Devoluciones";
        $info = Pages::findOrFail(4);
        return view('pages.devoluciones', compact('PaginaTitulo', 'info'));
    }

    public function terminosCondiciones()
    {
        $PaginaTitulo = "Términos y Condiciones";
        $info = Pages::findOrFail(5);
        return view('pages.terminosCondiciones', compact('PaginaTitulo', 'info'));
    }

    public function contacto()
    {
        $PaginaTitulo = "Contacto";
        $info = Configurations::findOrFail(1);
        return view('pages.contacto', compact('PaginaTitulo', 'info'));
    }

    public function seguimientoPedido()
    {
        $PaginaTitulo = "Seguimiento del Pedido";
        return view('pages.seguimientoPedido', compact('PaginaTitulo'));
    }

    public function buscadorProductos(Request $request)
    {
        $PaginaTitulo = "Búsqueda";
        $data = $request->dato;
        $categ = $request->categoria;

        if ($categ == null) {

            $type = true;
            $resultado = Products::where('title', 'LIKE', '%' . $request->dato . '%')
                ->where('active', 1)
                ->orderBy('created_at', 'DESC')
                ->get();
            return view('pages.buscador', compact('PaginaTitulo', 'resultado', 'data', 'type'));
        } else {

            $type = false;
            $resultado = DB::select("SELECT *, categories.title AS title_category, products.slug AS slug_producto,
            products.title AS title_product, products.id AS product_id FROM products, categories, products_categories
            where products.id = products_categories.products_id AND categories.id = products_categories.categories_id AND categories.id = {$categ}
            AND products.title LIKE '%{$data}%' AND products.active = 1");

            $images_product = array();
            foreach ($resultado as $key => $value) {
                $images_prod = ProductsGalleries::where('products_id', $value->product_id)->get();
                $images_product[] = $images_prod;
            }

            return view('pages.buscador')
                ->with('PaginaTitulo', $PaginaTitulo)
                ->with('resultado', $resultado)
                ->with('data', $data)
                ->with('type', $type)
                ->with('images_product', $images_product);
        }
    }

    public function allProducts()
    {
        $PaginaTitulo = "Productos";
        $products = Products::where('active', 1)->orderBy('created_at', 'DESC')->paginate(12);
        $productsN = Products::where('active', 1)->count();
        return view('pages.productos')
            ->with('PaginaTitulo', $PaginaTitulo)
            ->with('products', $products)
            ->with('productsN', $productsN);
    }

    public function allBlogs()
    {
        $PaginaTitulo = "Blog / Noticias";
        $blogs = Blog::where('active', 1)->orderBy('created_at', 'DESC')->get();
        return view('pages.blogs')
            ->with('PaginaTitulo', $PaginaTitulo)
            ->with('blogs', $blogs);
    }

    public function detalleBlog($id)
    {
        $blog = Blog::where('slug', $id)
            ->where('active', 1)
            ->firstOrFail();
        return view('pages.detalleBlog')
            ->with('blog', $blog);
    }






    public function comprafinal()
    {

        return view('pagina.comprafinal');
    }

    public function provinces($code)
    {

        $option_provinces  = '';
        $option_provinces .= '<option value="">Seleccionar</option>';

        $provinces = DB::select("SELECT code, description FROM provinces WHERE parent_code = :code ORDER BY description ASC", [':code' => $code]);

        if (count($provinces) > 0) {
            foreach ($provinces as $province) {
                $option_provinces .= '<option value="' . $province->code . '">' . $province->description . '</option>';
            }
        }

        echo $option_provinces;
    }

    public function locations($code)
    {

        $option_locations  = '';
        $option_locations .= '<option value="">Seleccionar</option>';

        $locations = DB::select("SELECT code, description FROM locations WHERE parent_code = :code ORDER BY description ASC", [':code' => $code]);

        if (count($locations) > 0) {
            foreach ($locations as $location) {
                $option_locations .= '<option value="' . $location->code . '">' . $location->description . '</option>';
            }
        }

        echo $option_locations;
    }

    public function despacho($code)
    {

        $valor_despacho  = '0';

        $despacho = DB::select("SELECT shipping_cost FROM locations WHERE code = :code", [':code' => $code]);

        if ($despacho) {
            $valor_despacho = $despacho[0]->shipping_cost;
        }

        echo $valor_despacho;
    }

    public function verPedido()
    {
    }
}
