<?php

use App\Http\Controllers\AttributeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ClientsAddressController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\ConfigDispatchController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\ConfigurationsController;
use App\Http\Controllers\CotizacionesController;
use App\Http\Controllers\CouriersController;
use App\Http\Controllers\DiscountsController;
use App\Http\Controllers\FaqsController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LocationsController;
use App\Http\Controllers\ModalsController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProvincesController;
use App\Http\Controllers\PublicitiesController;
use App\Http\Controllers\RegionsController;
use App\Http\Controllers\ResponsesController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleStatisticsController;
use App\Http\Controllers\SlidersClientsController;
use App\Http\Controllers\SlidersController;
use App\Http\Controllers\SlidersPartnersController;
use App\Http\Controllers\MetadataController;
use App\Http\Controllers\ProductsGalleriesController;
use App\Http\Controllers\UserController;
use App\Models\AttributesValues;
use App\Models\Cotizaciones;
use Application\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/admvisch/login', [LoginController::class, 'showAdminLoginForm'])->name('login.admmin');
Route::get('/login', [LoginController::class, 'showClientLoginForm'])->name('login.client');
Route::post('/admvisch/login', [LoginController::class, 'adminLogin'])->name('login.admin');
Route::post('/login', [LoginController::class, 'clientLogin'])->name('login.client');
Route::get('/notifications', [App\Http\Controllers\HomeController::class, 'index'])->name('home.notifications');
Route::get('/provinces/{id}', [App\Http\Controllers\HomeController::class, 'provinces'])->name('home.provinces');
Route::get('/locations/{id}', [App\Http\Controllers\HomeController::class, 'locations'])->name('home.locations');
Route::get('/despacho/{id}', [App\Http\Controllers\HomeController::class, 'despacho'])->name('home.despacho');

//carrito
Route::get('/mi-carrito', [App\Http\Controllers\CarroController::class, 'index'])->name('mi.carro');
Route::post('/mi-carrito/store', [App\Http\Controllers\CarroDetalleController::class, 'store'])->name('cart_details.store');
Route::post('/mi-carrito/destroy/{id}', [App\Http\Controllers\CarroDetalleController::class, 'destroy'])->name('cart_details.destroy');
Route::get('/mi-carrito/destroy/{id}', [App\Http\Controllers\CarroDetalleController::class, 'destroy'])->name('cart_details.destroy');
Route::post('/mi-carrito/update', [App\Http\Controllers\CarroController::class, 'update'])->name('micarro.update');

//registro - clientes
Route::get('/registro', [App\Http\Controllers\ClientsController::class, 'pagRegistro'])->name('registro');
Route::get('clients/provinces/{id}', [ClientsController::class, 'provinces'])->name('clients.provinces');
Route::get('clients/locations/{id}', [ClientsController::class, 'locations'])->name('clients.locations');
Route::post('clients/insert', [ClientsController::class, 'insertClientes'])->name('clientes.agregar');
Route::get('/recuperar-clave', [ClientsController::class, 'resetPassword'])->name('resetPassword');

// routas pagina principal
Route::get('/nosotros', [App\Http\Controllers\HomeController::class, 'nosotros'])->name('nosotros');
Route::get('/pago-seguro', [App\Http\Controllers\HomeController::class, 'pagoSeguro'])->name('pago.seguro');
Route::get('/politica-de-envios', [App\Http\Controllers\HomeController::class, 'politicaEnvios'])->name('politica.envios');
Route::get('/devoluciones', [App\Http\Controllers\HomeController::class, 'devoluciones'])->name('devoluciones');
Route::get('/terminos-y-condiciones', [App\Http\Controllers\HomeController::class, 'terminosCondiciones'])->name('terminosCondiciones');
Route::get('/contacto', [App\Http\Controllers\HomeController::class, 'contacto'])->name('contacto');
Route::get('/seguimiento-pedido', [App\Http\Controllers\HomeController::class, 'seguimientoPedido'])->name('seguimiento.pedido');
Route::get('/blog', [App\Http\Controllers\HomeController::class, 'allBlogs'])->name('all.blogs');
Route::get('/blog-detalle/{id}', [App\Http\Controllers\HomeController::class, 'detalleBlog'])->name('blog.detalle');

//mi cuenta
Route::get('/mi-cuenta', [App\Http\Controllers\MiCuentaController::class, 'miCuenta'])->name('mi.cuenta');
Route::get('/mis-pedidos', [App\Http\Controllers\MiCuentaController::class, 'misPedidos'])->name('mis.pedidos');
Route::get('/mis-datos-cuenta', [App\Http\Controllers\MiCuentaController::class, 'misDatos'])->name('mis.datos');
Route::get('/mis-puntos', [App\Http\Controllers\MiCuentaController::class, 'misPuntos'])->name('mis.puntos');
Route::post('/mis-datos-cuenta/update', [App\Http\Controllers\MiCuentaController::class, 'updateMisDatos'])->name('mis.datos.update');
Route::post('/mis-datos-cuenta/enter', [App\Http\Controllers\MiCuentaController::class, 'agregarNuevaDireccion'])->name('mis.datos.enter');
Route::get('/mis-datos-cuenta/delete/{id}', [App\Http\Controllers\MiCuentaController::class, 'eliminarDireccion'])->name('mis.datos.delete');
Route::post('/mis-datos-cuenta/address/update', [App\Http\Controllers\MiCuentaController::class, 'updateAddressDatos'])->name('datos.address.update');

//tienda
Route::get('/producto/{id}', [App\Http\Controllers\DetalleProductController::class, 'show'])->name('detalle.producto');
Route::get('/categoria-producto/{id}', [App\Http\Controllers\CategoriaProductoController::class, 'show'])->name('categoria.producto');
Route::get('/buscador', [App\Http\Controllers\HomeController::class, 'buscadorProductos'])->name('buscadorProductos');
Route::get('/ver-pedido/{id}', [App\Http\Controllers\HomeController::class, 'verPedido'])->name('ver-pedido');
Route::get('/productos', [App\Http\Controllers\HomeController::class, 'allProducts'])->name('allProducts');

Route::group(['middleware' => 'auth', 'prefix' => 'admvisch'], function () {

    //Rutas de index

    Route::post('index', [IndexController::class, 'sidebar'])->name('index.sidebar');
    Route::get('index/notifications', [IndexController::class, 'notifications'])->name('notifications');
    Route::get('index', [IndexController::class, 'index'])->name('index');
    Route::get('/', [IndexController::class, 'index'])->name('index');


    //Rutas de Ventas

    Route::get('orders', [OrdersController::class, 'index'])->name('orders');
    Route::post('orders', [OrdersController::class, 'index'])->name('orders');
    Route::post('orders/export/{id?}', [OrdersController::class, 'export'])->name('orders.export');
    Route::post('orders/documents/', [OrdersController::class, 'documents'])->name('orders.documents');
    Route::get('orders/viewCompact', [OrdersController::class, 'viewCompact'])->name('orders.viewCompact');
    Route::post('orders/viewCompact', [OrdersController::class, 'viewCompact'])->name('orders.viewCompact');

    Route::get('orders/view/{id}', [OrdersController::class, 'view'])->name('orders.view');
    Route::post('orders/update', [OrdersController::class, 'update'])->name('orders.update');



    //Rutas de avisos

    Route::get('modals', [ModalsController::class, 'index'])->name('modals');
    Route::get('modals/delete/{id}', [ModalsController::class, 'delete'])->name('modals.delete');
    Route::get('modals/enter/', [ModalsController::class, 'enter'])->name('modals.enter');

    Route::post('modals/insert', [ModalsController::class, 'insert'])->name('modals.insert');
    Route::get('modals/{id}/edit', [ModalsController::class, 'edit'])->name('modals.edit');
    Route::post('modals/update', [ModalsController::class, 'update'])->name('modals.update');
    Route::post('modals/orders', [ModalsController::class, 'orders'])->name('modals.orders');
    Route::post('modals/status', [ModalsController::class, 'status'])->name('modals.status');

    //Rutas de las cotizaciones

    Route::get('cotizaciones', [CotizacionesController::class, 'index'])->name('cotizaciones');
    Route::post('cotizaciones', [CotizacionesController::class, 'index'])->name('cotizaciones');
    Route::post('cotizaciones/export/{id?}', [CotizacionesController::class, 'export'])->name('cotizaciones.export');
    Route::post('cotizaciones/documents/', [CotizacionesController::class, 'documents'])->name('cotizaciones.documents');
    Route::get('cotizaciones/viewCompact', [CotizacionesController::class, 'viewCompact'])->name('cotizaciones.viewCompact');
    Route::get('cotizaciones/view/{id}', [CotizacionesController::class, 'view'])->name('cotizaciones.view');


    //Rutas de las estadisticas

    Route::get('saleStatistics', [SaleStatisticsController::class, 'index'])->name('saleStatistics');
    Route::post('saleStatistics', [SaleStatisticsController::class, 'index'])->name('saleStatistics');

    //Rutas de los Sliders Principal

    Route::get('sliders', [SlidersController::class, 'index'])->name('sliders');
    Route::post('sliders', [SlidersController::class, 'index'])->name('sliders');
    Route::get('sliders/delete/{id}', [SlidersController::class, 'delete'])->name('sliders.delete');
    Route::get('sliders/enter', [SlidersController::class, 'enter'])->name('sliders.enter');
    Route::post('sliders/insert', [SlidersController::class, 'insert'])->name('sliders.insert');
    Route::get('sliders/{id}/edit', [SlidersController::class, 'edit'])->name('sliders.edit');
    Route::post('sliders/orders', [SlidersController::class, 'orders'])->name('sliders.orders');
    Route::post('sliders/status', [SlidersController::class, 'status'])->name('sliders.status');
    Route::post('sliders/update', [SlidersController::class, 'update'])->name('sliders.update');

    //Rutas de los Sliders Clientes

    Route::get('slidersClients', [SlidersClientsController::class, 'index'])->name('slidersClients');
    Route::post('slidersClients', [SlidersClientsController::class, 'index'])->name('slidersClients');
    Route::get('slidersClients/enter', [SlidersClientsController::class, 'enter'])->name('slidersClients.enter');
    Route::post('slidersClients/insert', [SlidersClientsController::class, 'insert'])->name('slidersClients.insert');
    Route::get('slidersClients/{id}/edit', [SlidersClientsController::class, 'edit'])->name('slidersClients.edit');
    Route::post('slidersClients/orders', [SlidersClientsController::class, 'orders'])->name('slidersClients.orders');
    Route::get('slidersClients/delete/{id}', [SlidersClientsController::class, 'delete'])->name('slidersClients.delete');
    Route::post('slidersClients/status', [SlidersClientsController::class, 'status'])->name('slidersClients.status');
    Route::post('slidersClients/update', [SlidersClientsController::class, 'update'])->name('slidersClients.update');

    //Rutas de Representaciones

    Route::get('slidersPartners', [SlidersPartnersController::class, 'index'])->name('slidersPartners');
    Route::post('slidersPartners', [SlidersPartnersController::class, 'index'])->name('slidersPartners');
    Route::get('slidersPartners/enter', [SlidersPartnersController::class, 'enter'])->name('slidersPartners.enter');
    Route::post('slidersPartners/insert', [SlidersPartnersController::class, 'insert'])->name('slidersPartners.insert');
    Route::get('slidersPartners/{id}/edit', [SlidersPartnersController::class, 'edit'])->name('slidersPartners.edit');
    Route::post('slidersPartners/orders', [SlidersPartnersController::class, 'orders'])->name('slidersPartners.orders');
    Route::get('slidersPartners/delete/{id}', [SlidersPartnersController::class, 'delete'])->name('slidersPartners.delete');
    Route::post('slidersPartners/status', [SlidersPartnersController::class, 'status'])->name('slidersPartners.status');
    Route::post('slidersPartners/update', [SlidersPartnersController::class, 'update'])->name('slidersPartners.update');


    //preguntas frecuentes

    Route::get('faqs', [FaqsController::class, 'index'])->name('faqs');
    Route::post('faqs', [FaqsController::class, 'index'])->name('faqs');
    Route::get('faqs/enter', [FaqsController::class, 'enter'])->name('faqs.enter');
    Route::post('faqs/insert', [FaqsController::class, 'insert'])->name('faqs.insert');
    Route::get('faqs/{id}/edit', [FaqsController::class, 'edit'])->name('faqs.edit');
    Route::post('faqs/update', [FaqsController::class, 'update'])->name('faqs.update');
    Route::post('faqs/orders', [FaqsController::class, 'orders'])->name('faqs.orders');
    Route::get('faqs/delete/{id}', [FaqsController::class, 'delete'])->name('faqs.delete');
    Route::post('faqs/status', [FaqsController::class, 'status'])->name('faqs.status');


    //atributos

    Route::get('attributes', [AttributeController::class, 'index'])->name('attributes');
    Route::post('attributes', [AttributeController::class, 'index'])->name('attributes');
    Route::get('attributes/enter', [AttributeController::class, 'enter'])->name('attributes.enter');
    Route::post('attributes/insert', [AttributeController::class, 'insert'])->name('attributes.insert');
    Route::get('attributes/{id}/edit', [AttributeController::class, 'edit'])->name('attributes.edit');
    Route::post('attributes/update', [AttributeController::class, 'update'])->name('attributes.update');
    Route::get('attributes/delete/{id}', [AttributeController::class, 'delete'])->name('attributes.delete');
    Route::post('attributes/status', [AttributeController::class, 'status'])->name('attributes.status');


    // blog - admin

    Route::get('admin-blog', [BlogController::class, 'index'])->name('admin.blog');
    Route::post('admin-blog', [BlogController::class, 'index'])->name('admin.blog');
    Route::get('admin-blog/enter', [BlogController::class, 'enter'])->name('blog.enter');
    Route::post('admin-blog/insert', [BlogController::class, 'insert'])->name('blog.insert');
    Route::get('admin-blog/{id}/edit', [BlogController::class, 'edit'])->name('blog.edit');
    Route::post('admin-blog/update', [BlogController::class, 'update'])->name('blog.update');
    Route::get('admin-blog/delete/{id}', [BlogController::class, 'delete'])->name('blog.delete');
    Route::post('admin-blog/status', [BlogController::class, 'status'])->name('blog.status');
    Route::post('upload_image_blog', [BlogController::class, 'uploadImage_editor'])->name('upload.blog');


    //Config dispatch

    Route::get('dispatch', [ConfigDispatchController::class, 'index'])->name('config.dispatch');
    Route::post('dispatch', [ConfigDispatchController::class, 'index'])->name('config.dispatch');
    Route::get('dispatch/enter', [ConfigDispatchController::class, 'enter'])->name('config.dispatch.enter');
    Route::post('dispatch/insert', [ConfigDispatchController::class, 'insert'])->name('config.dispatch.insert');
    Route::get('dispatch/{id}/edit', [ConfigDispatchController::class, 'edit'])->name('config.dispatch.edit');
    Route::post('dispatch/update', [ConfigDispatchController::class, 'update'])->name('config.dispatch.update');
    Route::get('dispatch/delete/{id}', [ConfigDispatchController::class, 'delete'])->name('config.dispatch.delete');
    Route::post('dispatch/status', [ConfigDispatchController::class, 'status'])->name('config.dispatch.status');
    Route::post('dispatch/retiroTienda', [ConfigDispatchController::class, 'statusRetiroTienda'])->name('config.dispatch.retiro.tienda');

    //Rutas de Mini Banner Home

    Route::get('publicities', [PublicitiesController::class, 'index'])->name('publicities');
    Route::post('publicities', [PublicitiesController::class, 'index'])->name('publicities');
    Route::get('publicities/enter', [PublicitiesController::class, 'enter'])->name('publicities.enter');
    Route::post('publicities/insert', [PublicitiesController::class, 'insert'])->name('publicities.insert');
    Route::get('publicities/{id}/edit', [PublicitiesController::class, 'edit'])->name('publicities.edit');
    Route::post('publicities/orders', [PublicitiesController::class, 'orders'])->name('publicities.orders');
    Route::get('publicities/delete/{id}', [PublicitiesController::class, 'delete'])->name('publicities.delete');
    Route::post('publicities/status', [PublicitiesController::class, 'status'])->name('publicities.status');
    Route::post('publicities/update', [PublicitiesController::class, 'update'])->name('publicities.update');

    //Rutas de Paginas

    Route::get('pages', [PagesController::class, 'index'])->name('pages');
    Route::post('pages', [PagesController::class, 'index'])->name('pages');
    Route::get('pages/enter', [PagesController::class, 'enter'])->name('pages.enter');
    Route::post('pages/insert', [PagesController::class, 'insert'])->name('pages.insert');
    Route::get('pages/{id}/edit', [PagesController::class, 'edit'])->name('pages.edit');
    Route::get('pages/delete/{id}', [PagesController::class, 'delete'])->name('pages.delete');
    Route::post('pages/status', [PagesController::class, 'status'])->name('pages.status');
    Route::post('pages/update', [PagesController::class, 'update'])->name('pages.update');


    //Rutas de Respuestas a paginas

    Route::get('responses', [ResponsesController::class, 'index'])->name('responses');
    Route::post('responses', [ResponsesController::class, 'index'])->name('responses');
    Route::get('responses/enter', [ResponsesController::class, 'enter'])->name('responses.enter');
    Route::post('responses/insert', [ResponsesController::class, 'insert'])->name('responses.insert');
    Route::get('responses/{id}/edit', [ResponsesController::class, 'edit'])->name('responses.edit');
    Route::get('responses/delete/{id}', [ResponsesController::class, 'delete'])->name('responses.delete');
    Route::post('responses/status', [ResponsesController::class, 'status'])->name('responses.status');
    Route::post('responses/update', [ResponsesController::class, 'update'])->name('responses.update');

    //Rutas de Sucursales

    Route::get('offices', [OfficeController::class, 'index'])->name('offices');
    Route::post('offices', [OfficeController::class, 'index'])->name('offices');
    Route::get('offices/enter', [OfficeController::class, 'enter'])->name('offices.enter');
    Route::post('offices/insert', [OfficeController::class, 'insert'])->name('offices.insert');
    Route::get('offices/{id}/edit', [OfficeController::class, 'edit'])->name('offices.edit');
    Route::post('offices/orders', [OfficeController::class, 'orders'])->name('offices.orders');
    Route::get('offices/delete/{id}', [OfficeController::class, 'delete'])->name('offices.delete');
    Route::post('offices/status', [OfficeController::class, 'status'])->name('offices.status');
    Route::post('offices/update', [OfficeController::class, 'update'])->name('offices.update');

    //Rutas de Regiones

    Route::get('regions', [RegionsController::class, 'index'])->name('regions');
    Route::post('regions', [RegionsController::class, 'index'])->name('regions');
    Route::get('regions/enter', [RegionsController::class, 'enter'])->name('regions.enter');
    Route::post('regions/insert', [RegionsController::class, 'insert'])->name('regions.insert');
    Route::get('regions/{id}/edit', [RegionsController::class, 'edit'])->name('regions.edit');
    Route::post('regions/orders', [RegionsController::class, 'orders'])->name('regions.orders');
    Route::get('regions/delete/{id}', [RegionsController::class, 'delete'])->name('regions.delete');
    Route::post('regions/status', [RegionsController::class, 'status'])->name('regions.status');
    Route::post('regions/update', [RegionsController::class, 'update'])->name('regions.update');

    //Rutas de Provincias

    Route::get('provinces', [ProvincesController::class, 'index'])->name('provinces');
    Route::post('provinces', [ProvincesController::class, 'index'])->name('provinces');
    Route::get('provinces/enter', [ProvincesController::class, 'enter'])->name('provinces.enter');
    Route::post('provinces/insert', [ProvincesController::class, 'insert'])->name('provinces.insert');
    Route::get('provinces/{id}/edit', [ProvincesController::class, 'edit'])->name('provinces.edit');
    Route::post('provinces/orders', [ProvincesController::class, 'orders'])->name('provinces.orders');
    Route::get('provinces/delete/{id}', [ProvincesController::class, 'delete'])->name('provinces.delete');
    Route::post('provinces/status', [ProvincesController::class, 'status'])->name('provinces.status');
    Route::post('provinces/update', [ProvincesController::class, 'update'])->name('provinces.update');


    //Rutas de Comunas

    Route::get('locations', [LocationsController::class, 'index'])->name('locations');
    Route::post('locations', [LocationsController::class, 'index'])->name('locations');
    Route::get('locations/enter', [LocationsController::class, 'enter'])->name('locations.enter');
    Route::post('locations/insert', [LocationsController::class, 'insert'])->name('locations.insert');
    Route::get('locations/provinces/{id}', [LocationsController::class, 'provinces'])->name('locations.provinces');
    Route::get('locations/{id}/edit', [LocationsController::class, 'edit'])->name('locations.edit');
    Route::post('locations/orders', [LocationsController::class, 'orders'])->name('locations.orders');
    Route::get('locations/delete/{id}', [LocationsController::class, 'delete'])->name('locations.delete');
    Route::post('locations/status', [LocationsController::class, 'status'])->name('locations.status');
    Route::post('locations/update', [LocationsController::class, 'update'])->name('locations.update');


    //Rutas de Clientes

    Route::get('clients', [ClientsController::class, 'index'])->name('clients');
    Route::post('clients', [ClientsController::class, 'index'])->name('clients');
    Route::post('clients/documents', [ClientsController::class, 'documents'])->name('clients.documents');
    Route::get('clients/enter', [ClientsController::class, 'enter'])->name('clients.enter');
    Route::post('clients/export/{all?}', [ClientsController::class, 'export'])->name('clients.export');
    Route::get('clients/export/{all?}', [ClientsController::class, 'export'])->name('clients.export');
    Route::post('clients/insert', [ClientsController::class, 'insert'])->name('clients.insert');
    Route::get('clients/provinces/{id}', [ClientsController::class, 'provinces'])->name('clients.provinces');
    Route::get('clients/locations/{id}', [ClientsController::class, 'locations'])->name('clients.locations');
    Route::get('clients/edit/{id}', [ClientsController::class, 'edit'])->name('clients.edit');
    Route::post('clients/edit/{id}', [ClientsController::class, 'edit'])->name('clients.edit');
    Route::post('clients/update', [ClientsController::class, 'update'])->name('clients.update');
    Route::get('clients/delete/{id}', [ClientsController::class, 'delete'])->name('clients.delete');
    Route::get('clients/delete/{id}', [ClientsController::class, 'delete'])->name('clients.delete');
    Route::post('clients/upload', [ClientsController::class, 'upload'])->name('clients.upload');
    Route::post('clients/loadClientsByExcel', [ClientsController::class, 'loadClientsByExcel'])->name('clients.loadClientsByExcel');
    Route::get('clients/import', [ClientsController::class, 'import'])->name('clients.import');
    Route::post('clients/status', [ClientsController::class, 'status'])->name('clients.status');





    //Ruta de Clientes Address

    Route::get('clientsAddress', [ClientsAddressController::class, 'index'])->name('clientsAddress');
    Route::post('clientsAddress', [ClientsAddressController::class, 'index'])->name('clientsAddress');
    Route::post('clientsAddress/update', [ClientsAddressController::class, 'update'])->name('clientsAddress.update');
    Route::post('clientsAddress/edit/{id}', [ClientsAddressController::class, 'edit'])->name('clientsAddress.edit');
    Route::get('clientsAddress/delete/{id}', [ClientsAddressController::class, 'delete'])->name('clientsAddress.delete');
    Route::post('clientsAddress/insert', [ClientsAddressController::class, 'insert'])->name('clientsAddress.insert');
    Route::post('clientsAddress/status', [ClientsAddressController::class, 'status'])->name('clientsAddress.status');



    //Rutas de Cupones

    Route::get('discounts', [DiscountsController::class, 'index'])->name('discounts');
    Route::post('discounts', [DiscountsController::class, 'index'])->name('discounts');
    Route::get('discounts/enter', [DiscountsController::class, 'enter'])->name('discounts.enter');
    Route::post('discounts/insert', [DiscountsController::class, 'insert'])->name('discounts.insert');
    Route::get('discounts/edit/{id}', [DiscountsController::class, 'edit'])->name('discounts.edit');
    Route::post('discounts/update', [DiscountsController::class, 'update'])->name('discounts.update');
    Route::get('discounts/delete/{id}', [DiscountsController::class, 'delete'])->name('discounts.delete');
    Route::post('discounts/status', [DiscountsController::class, 'status'])->name('discounts.status');
    Route::post('discounts/get/Brands', [DiscountsController::class, 'getBrands'])->name('discounts.brands');
    Route::post('discounts/get/Products', [DiscountsController::class, 'getProducts'])->name('discounts.products');
    Route::post('discounts/get/Categories', [DiscountsController::class, 'getCategories'])->name('discounts.categories');
    Route::post('discounts/get/Clients', [DiscountsController::class, 'getClients'])->name('discounts.clients');
    Route::post('discounts/orders', [DiscountsController::class, 'orders'])->name('discounts.orders');


    //Rutas de Marcas

    Route::get('brands', [BrandsController::class, 'index'])->name('brands');
    Route::post('brands', [BrandsController::class, 'index'])->name('brands');
    Route::get('brands/enter', [BrandsController::class, 'enter'])->name('brands.enter');
    Route::post('brands/insert', [BrandsController::class, 'insert'])->name('brands.insert');
    Route::get('brands/{id}/edit', [BrandsController::class, 'edit'])->name('brands.edit');
    Route::post('brands/orders', [BrandsController::class, 'orders'])->name('brands.orders');
    Route::get('brands/delete/{id}', [BrandsController::class, 'delete'])->name('brands.delete');
    Route::post('brands/status', [BrandsController::class, 'status'])->name('brands.status');
    Route::post('brands/update', [BrandsController::class, 'update'])->name('brands.update');

    //Rutas de Categorias

    Route::get('categories', [CategoriesController::class, 'index'])->name('categories');
    Route::post('categories', [CategoriesController::class, 'index'])->name('categories');
    Route::get('categories/enter', [CategoriesController::class, 'enter'])->name('categories.enter');
    Route::post('categories/insert', [CategoriesController::class, 'insert'])->name('categories.insert');
    Route::get('categories/{id}/edit', [CategoriesController::class, 'edit'])->name('categories.edit');
    Route::post('categories/orders', [CategoriesController::class, 'orders'])->name('categories.orders');
    Route::get('categories/delete/{id}', [CategoriesController::class, 'delete'])->name('categories.delete');
    Route::post('categories/status', [CategoriesController::class, 'status'])->name('categories.status');
    Route::post('categories/update', [CategoriesController::class, 'update'])->name('categories.update');
    Route::post('categories/getProductsRelations', [CategoriesController::class, 'getProductsRelations'])->name('categories.getProductsRelations');

    //Rutas de Productos

    Route::get('products', [ProductsController::class, 'index'])->name('products');
    Route::post('products', [ProductsController::class, 'index'])->name('products');
    Route::post('products/documents/', [ProductsController::class, 'documents'])->name('products.documents');
    Route::get('products/enter', [ProductsController::class, 'enter'])->name('products.enter');
    Route::post('products/insert', [ProductsController::class, 'insert'])->name('products.insert');
    Route::get('products/edit/{id}', [ProductsController::class, 'edit'])->name('products.edit');
    Route::post('products/edit/{id}', [ProductsController::class, 'edit'])->name('products.edit');

    Route::post('products/status', [ProductsController::class, 'status'])->name('products.status');
    Route::get('products/export/{id?}', [ProductsController::class, 'export'])->name('products.export');
    Route::get('products/exportGalleries/{id?}', [ProductsController::class, 'exportGalleries'])->name('products.exportGalleries');
    Route::post('products/orders', [ProductsController::class, 'orders'])->name('products.orders');
    Route::post('products/upload', [ProductsController::class, 'upload'])->name('products.upload');
    Route::post('products/import/{id?}', [ProductsController::class, 'import'])->name('products.import');
    Route::post('products/loadProductsByExcel', [ProductsController::class, 'loadProductsByExcel'])->name('products.loadProductsByExcel');
    Route::post('products/importGalleries', [ProductsController::class, 'importGalleries'])->name('products.importGalleries');
    Route::post('products/getProductsRelations', [ProductsController::class, 'getProductsRelations'])->name('products.getProductsRelations');
    Route::post('products/update', [ProductsController::class, 'update'])->name('products.update');
    Route::get('products/delete/{id}', [ProductsController::class, 'delete'])->name('products.delete');
    Route::get('products/import', [ProductsController::class, 'import'])->name('products.import');
    Route::get('products/importGalleries', [ProductsController::class, 'importGalleries'])->name('products.importGalleries');
    Route::post('products/uploadGalleries', [ProductsController::class, 'uploadGalleries'])->name('products.uploadGalleries');
    Route::post('products/loadGalleriesByExcel', [ProductsController::class, 'loadGalleriesByExcel'])->name('products.loadGalleriesByExcel');

    Route::get('products/attribute/get/{id}', [AttributeController::class, 'getAttributeId'])->name('products.attribute.get');

    //Products Galleries

    Route::get('productsGalleries', [ProductsGalleriesController::class, 'index'])->name('productsGalleries');
    Route::post('productsGalleries', [ProductsGalleriesController::class, 'index'])->name('productsGalleries');
    Route::post('productsGalleries/insert', [ProductsGalleriesController::class, 'insert'])->name('productsGalleries.insert');
    Route::get('productsGalleries/insert', [ProductsGalleriesController::class, 'insert'])->name('productsGalleries.insert');
    Route::post('productsGalleries/update', [ProductsGalleriesController::class, 'update'])->name('productsGalleries.update');
    Route::get('productsGalleries/delete/{id}', [ProductsGalleriesController::class, 'delete'])->name('productsGalleries.delete');
    Route::get('productsGalleries/edit/{id}', [ProductsGalleriesController::class, 'edit'])->name('productsGalleries.edit');
    Route::get('productsGalleries/import', [ProductsGalleriesController::class, 'index'])->name('productsGalleries.import');




    //Rutas de Empresas de Despacho

    Route::get('couriers', [CouriersController::class, 'index'])->name('couriers');
    Route::post('couriers', [CouriersController::class, 'index'])->name('couriers');
    Route::get('couriers/enter', [CouriersController::class, 'enter'])->name('couriers.enter');
    Route::post('couriers/insert', [CouriersController::class, 'insert'])->name('couriers.insert');
    Route::get('couriers/provinces/{id}', [CouriersController::class, 'provinces'])->name('couriers.provinces');
    Route::get('couriers/{id}/edit', [CouriersController::class, 'edit'])->name('couriers.edit');
    Route::post('couriers/orders', [CouriersController::class, 'orders'])->name('couriers.orders');
    Route::get('couriers/delete/{id}', [CouriersController::class, 'delete'])->name('couriers.delete');
    Route::post('couriers/status', [CouriersController::class, 'status'])->name('couriers.status');
    Route::post('couriers/update', [CouriersController::class, 'update'])->name('couriers.update');


    //Rutas de contactos

    Route::get('contacts', [ContactsController::class, 'index'])->name('contacts');
    Route::get('contacts/view/{id}', [ContactsController::class, 'view'])->name('contacts.view');
    Route::post('contacts', [ContactsController::class, 'index'])->name('contacts');
    Route::post('contacts/documents/', [ContactsController::class, 'documents'])->name('contacts.documents');
    Route::post('contacts/viewCompact', [ContactsController::class, 'viewCompact'])->name('contacts.viewCompact');

    //Rutas de Roles

    Route::get('roles', [RoleController::class, 'index'])->name('roles');
    Route::post('roles', [RoleController::class, 'index'])->name('roles');
    Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('roles/store', [RoleController::class, 'store'])->name('roles.store');
    Route::get('roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::get('roles/delete/{id}', [RoleController::class, 'destroy'])->name('roles.delete');
    Route::post('roles/update', [RoleController::class, 'update'])->name('roles.update');

    //Rutas de Parametros Globales

    Route::get('configurations', [ConfigurationsController::class, 'index'])->name('configurations');
    Route::post('configurations', [ConfigurationsController::class, 'index'])->name('configurations');
    Route::get('configurations/create', [ConfigurationsController::class, 'create'])->name('configurations.create');
    Route::post('configurations/update', [ConfigurationsController::class, 'update'])->name('configurations.update');
    Route::get('configurations/{id}/edit', [ConfigurationsController::class, 'edit'])->name('configurations.edit');
    Route::post('configurations/status', [ConfigurationsController::class, 'status'])->name('configurations.status');

    //Rutas de Metadatos

    Route::get('metadata', [MetadataController::class, 'index'])->name('metadata');
    Route::post('metadata', [MetadataController::class, 'index'])->name('metadata');
    Route::get('metadata/create', [MetadataController::class, 'create'])->name('metadata.create');
    Route::post('metadata/update', [MetadataController::class, 'update'])->name('metadata.update');
    Route::get('metadata/{id}/edit', [MetadataController::class, 'edit'])->name('metadata.edit');

    //Rutas de Usuarios

    Route::get('users', [UserController::class, 'index'])->name('users');
    Route::post('users', [UserController::class, 'index'])->name('users');
    Route::get('users/enter', [UserController::class, 'enter'])->name('users.enter');
    Route::post('users/insert', [UserController::class, 'insert'])->name('users.insert');
    Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::get('users/delete/{id}', [UserController::class, 'delete'])->name('users.delete');
    Route::post('users/update', [UserController::class, 'update'])->name('users.update');
    Route::post('users/status', [UserController::class, 'status'])->name('users.status');
});

Route::get('logout', [LoginController::class, 'logout']);
