<?php

namespace App\Http\Controllers;

use App\Models\Brands;
use App\Models\Categories;
use App\Models\Log;
use App\Models\Products;
use App\Models\ProductsCategories;
use App\Models\ProductsGalleries;
use App\Models\ProductsRelations;
use App\Models\Types;
use App\Models\Attribute;
use Application\Helper;
use Application\HelperExcel;
use Illuminate\Http\Request;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\Auth;
use Libraries\Upload\Upload;
use PHPExcel;
use PHPExcel_Reader_Excel2007;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class ProductsController extends Controller
{

    private $title = 'Productos';
    private $parent_title = 'Tienda Virtual';
    private $module = 'products';
    private $image_description = '800 x 600';
    private $archive_description = 'PDF';


    public function index()
    {



        $content = '';
        $contentCategories = $this->generateCategoriesForIndex(0, 1, 3, 0, 0, $content);


        $content = '';
        $contentBrands = $this->generateBrandsForIndex();


        return view('admvisch.products.index')->with(['categories' => $contentCategories, 'brands' => $contentBrands, 'title' => $this->title, 'parent_title' => $this->parent_title, 'module' => $this->module, 'image_description' => $this->image_description, 'archive_description' => $this->archive_description]);
    }

    public function documents()
    {
        $table_documents_body = array();

        $products = Products::where('id', '<>', '');

        if (isset($_POST['active']) && !empty($_POST['active'])) {
            $active = ($_POST['active'] == 'A') ? 1 : 0;
            $products = $products->where('active', $active);
        }

        if (isset($_POST['stock']) && !empty($_POST['stock'])) {
            $stock = ($_POST['stock'] == 'C') ? '>' : '<';
            $products = $products->where('stock', $stock, 0);
        }

        if (isset($_POST['brand']) && !empty($_POST['brand'])) {
            $brand_id = Helper::postValue('brand', 0);
            $products = $products->whereIn('brands_id', function ($query) use ($brand_id) {
                $query->select('id')
                    ->from('brands')
                    ->where('id', $brand_id);
            });
        } else if (isset($_POST['category']) && !empty($_POST['category'])) {
            $category_id = Helper::postValue('category', 0);
            $products = $products->whereIn('id', function ($query) use ($category_id) {
                $query->select('products_categories.products_id')
                    ->from('products_categories')
                    ->join('categories', 'products_categories.categories_id', '=', 'categories.id')
                    ->whereRaw('products_categories.products_id = products.id')
                    ->where('categories.id', $category_id);
            });
        }

        $products = $products->orderBy('id', 'DESC')->get();

        if ($products->count() > 0) {
            foreach ($products as $product) {

                $categories = '';
                foreach ($product->categories as $category) {
                    $categories .= $category->category->title . ',<br> ';
                }

                if (!empty($categories)) {
                    $categories = substr($categories, 0, -6);
                }

                $status = $actions = '';

                $class_status = ($product->active == 1) ? "success" : "default";
                $text_status = ($product->active == 1) ? "Activo" : "Inactivo";

                $status = '<a style="cursor: pointer;" class="change-status" id="' . $product->id . '"><span class="badge badge-' . $class_status . '">' . $text_status . '</span></a>';



                $actions .= '<a type="button" class="btn btn-sm btn-gold" data-toggle="tooltip" data-placement="top" title="" data-original-title="Editar" href="' . URL_FRIENDLY_BASE . $this->module . '/edit/' . $product->id . '"><i class="fa fa fa-pencil-square-o"></i></a> ';



                $actions .= '<a type="button" class="btn btn-sm btn-danger delete-register" data-toggle="tooltip" data-placement="top" title="" data-original-title="Eliminar" id="' .  $product->id . '"><i class="fa fa-trash-o"></i></a>';


                $table_documents_body[] = array(
                    '#' . $product->sku,
                    $product->title,
                    $categories,
                    Helper::formatDecimals($product->stock, 0),
                    $status,
                    Helper::dateFormatUser($product->updated_at),
                    $product->author,
                    $actions
                );
            }
        }

        //--------------------------------------------------------------------------------------------------------------------------------------

        echo json_encode(array(
            'data' => $table_documents_body
        ));
    }

    public function enter()
    {


        $content = '';
        $contentCategories = $this->generateCategoriesSelect(0, 1, array(), $content);

        $brands = Brands::where(['active' => 1])->orderBy('title')->get();

        $types = Types::where(['active' => 1])->orderBy('title')->get();

        $attributes = Attribute::where('active', 1)->get();

        return view('admvisch.products.enter')
            ->with([
                'categories' => $contentCategories,
                'brands' => $brands, 'title' => $this->title,
                'parent_title' => $this->parent_title,
                'module' => $this->module, 'types' => $types,
                'image_description' => $this->image_description,
                'archive_description' => $this->archive_description,
                'attributes' => $attributes
            ]);
    }

    public function insert()
    {

        $products = Products::where(['sku' => Helper::postValue('sku')])->get()->count();

        if ($products > 0) {
            session()->flash('error', 'failure');
            return redirect()->route('products.enter');
        } else {

            $archive = '';
            if (isset($_FILES) && isset($_FILES['archive']) && $_FILES['archive']['size'] > 0) {
                $archive = Helper::uploadPdf($directoryUpload = UPLOAD_URL_ROOT . $this->module . DS . 'pdf', $inputName = 'archive');
                if ($archive == 'error') {
                    $this->redirect(URL_FRIENDLY_BASE . $this->module . '/enter/upload/pdf');
                }
            }
            $certificado = '';
            if (isset($_FILES) && isset($_FILES['certificado']) && $_FILES['certificado']['size'] > 0) {
                $certificado = Helper::uploadPdf($directoryUpload = UPLOAD_URL_ROOT . $this->module . DS . 'pdf', $inputName = 'certificado');
                if ($certificado == 'error') {
                    $this->redirect(URL_FRIENDLY_BASE . $this->module . '/enter/upload/pdf');
                }
            }

            $position = 0;
            $positions = DB::table($this->module)->max('position');
            $position = $positions + 1;

            /*************************************************************************************************************************************************/
            /* CATEGORIAS */
            /*************************************************************************************************************************************************/

            $postProductsCategories = (isset($_POST["categories"])) ? $_POST["categories"] : array();
            $verifiedCategories = array();

            if (count($postProductsCategories)) {
                foreach ($postProductsCategories as $key => $productCategory) {
                    $level_category = self::getLevelCategory($productCategory);
                    if ($level_category > 0) {
                        list($categoryLevel1, $categoryLevel2, $categoryLevel3) = self::getTreeCategories($productCategory, $level_category);

                        if (isset($categoryLevel1) && !empty($categoryLevel1)) {
                            $verifiedCategories[] = $categoryLevel1->id;
                        }
                        if (isset($categoryLevel2) && !empty($categoryLevel2)) {
                            $verifiedCategories[] = $categoryLevel2->id;
                        }
                        if (isset($categoryLevel3) && !empty($categoryLevel3)) {
                            $verifiedCategories[] = $categoryLevel3->id;
                        }
                    }
                }

                array_push($postProductsCategories, $verifiedCategories);
            } else {
                $verifiedCategories = $postProductsCategories;
            }

            foreach ($verifiedCategories as $key => $valor) {
                if (($kt = array_search($valor, $verifiedCategories)) !== false and $key != $kt) {
                    unset($verifiedCategories[$kt]);
                }
            }

            $verifiedCategories = array_merge($verifiedCategories);

            /*************************************************************************************************************************************************/

            $start_date = (isset($_POST['start_date']) && !empty($_POST['start_date'])) ? Helper::dateFormatSystem(Helper::postValue('start_date')) : '';
            $end_date = (isset($_POST['end_date']) && !empty($_POST['end_date'])) ? Helper::dateFormatSystem(Helper::postValue('end_date')) : '';

            $post = array(
                'brands_id' => 1,
                'slug' => Str::slug(Helper::postValue('title'), '-'),
                'types_id' => Helper::postValue('types_id'),
                'type' => Helper::postValue('type'),
                'sku' => Helper::postValue('sku'),
                'title' => Helper::postValue('title'),
                'normal_price' => Helper::postValue('normal_price', 0),
                'offer_price' => Helper::postValue('offer_price', 0),
                'stock_control' => Helper::postValue('stock_control', 0),
                'stock' => Helper::postValue('stock', 0),
                'minimum_amount' => Helper::postValue('minimum_amount', 0),
                'discounts' => Helper::postValue('discount', 0),
                'weight' => Helper::postValue('weight', 0),
                'lenght' => Helper::postValue('lenght', 0),
                'width' => Helper::postValue('width', 0),
                'height' => Helper::postValue('height', 0),
                'general_description' => Helper::postValue('general_description'),
                'technical_description' => Helper::postValue('technical_description'),
                'shipping_description' => Helper::postValue('shipping_description'),
                'guarantee_description' => Helper::postValue('guarantee_description'),
                'video_description' => Helper::postValue('video_description'),
                'model' => Helper::postValue('model'),
                'color' => Helper::postValue('color', ''),
                'talla' => Helper::postValue('talla', ''),
                'medida' => Helper::postValue('medida'),
                'offer' => Helper::postValue('offer', 0),
                'featured' => Helper::postValue('featured', 0),
                'new' => Helper::postValue('new', 0),
                'a_pedido' => Helper::postValue('a_pedido', 0),
                'visit_number' => Helper::postValue('visits_number', 0),
                'sales_number' => Helper::postValue('sales_number', 0),
                'points' => Helper::postValue('points', 0),
                'position' => $position,
                'shipping_active' => Helper::postValue('shipping_active', 0),
                'office_shipping_active' => Helper::postValue('office_shipping_active', 0),
                'shipping_free' => Helper::postValue('shipping_free', 0),
                'attribute_active' => Helper::postValue('attribute_active', 0),
                'meta_title' => Helper::postValue('meta_title'),
                'meta_description' => Helper::postValue('meta_description'),
                'meta_keyword' => Helper::postValue('meta_keyword'),
                'meta_author' => Helper::postValue('meta_author'),
                'meta_robots' => Helper::postValue('meta_robots'),
                'archive' => $archive,
                'certificado' => $certificado,
                'chilecompracode' => Helper::postValue('chilecompracode'),
                'active' => Helper::postValue('active', 0),
                'author' =>  Auth::user()->name
            );

            if (!empty($start_date) && $start_date != '0000-00-00') {
                $post['start_date'] = $start_date;
            }

            if (!empty($end_date) && $end_date != '0000-00-00') {
                $post['end_date'] = $end_date;
            }

            if ($insert = Products::create($post)) {
                $id = $insert->id;

                if (count($verifiedCategories) > 0) {
                    foreach ($verifiedCategories as $category_id) {
                        $post = array(
                            'products_id' => $id,
                            'categories_id' => $category_id
                        );
                        ProductsCategories::create($post);
                    }
                }

                $productsRelations = (isset($_POST["relations"]) && !empty($_POST["relations"])) ? $_POST["relations"] : array();
                if (count($productsRelations) > 0) {
                    foreach ($productsRelations as $product_id) {
                        $post = array(
                            'products_id' => $id,
                            'relation_id' => $product_id
                        );
                        ProductsRelations::create($post);
                    }
                }

                if (LOG_GENERATE === true) {
                    Log::create([
                        'id_user' => Helper::sessionSystemValue('id'),
                        'date' => Helper::getDate($hour = false),
                        'hour' => Helper::getHour(),
                        'ip' => Helper::getIP(),
                        'module' => $this->module,
                        'action' => 'INGRESO',
                        'identifier' => $id,
                        'detail' => 'Ingresó nuevo producto "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                    ]);
                }

                session()->flash('error', 'success');
                return redirect()->route('products.edit', $id);
            } else {
                session()->flash('error', 'failure');
                return redirect()->route('products.enter');
            }
        }
    }

    public function edit($id)
    {


        $product = Products::findOrFail($id);

        $product->start_date = ($product->start_date == '0000-00-00' || $product->start_date == '') ? '' : Helper::dateFormatUser($product->start_date, false);
        $product->end_date = ($product->end_date == '0000-00-00'  || $product->end_date == '') ? '' : Helper::dateFormatUser($product->end_date, false);

        // CATEGORIAS SELECCIONADAS
        $keysProductsCategoriesSelected = array();

        $categories = ProductsCategories::where(['products_id' => $product->id])->get();
        if (count($categories) > 0) {
            foreach ($categories as $category) {
                $keysProductsCategoriesSelected[] = $category->categories_id;
            }
        }

        // GENERACIÓN DE LISTA MENU PARA CATEGORIAS
        $content = '';
        $contentCategories = $this->generateCategoriesSelect(0, 1, $keysProductsCategoriesSelected, $content);

        // MARCAS
        $brands = Brands::where(['active' => 1])->orderBy('title')->get();

        // TIPOS
        $types = Types::where(['active' => 1])->orderBy('title')->get();


        return view('admvisch.products.edit')->with(['categories' => $contentCategories, 'brands' => $brands, 'title' => $this->title, 'parent_title' => $this->parent_title, 'module' => $this->module, 'types' => $types, 'product' => $product, 'image_description' => $this->image_description, 'archive_description' => $this->archive_description]);
    }

    public function update()
    {

        $id = Helper::postValue('id');

        $products = Products::where('sku', Helper::postValue('sku'))->where('id', '<>', $id)->get()->count();

        if ($products > 0) {
            session()->flash('error', 'duplicate');
            return redirect()->route('products.edit', $id);
        } else {

            $archive = '';
            if (isset($_FILES) && isset($_FILES['archive']) && $_FILES['archive']['size'] > 0) {
                $archive = Helper::uploadPdf($directoryUpload = UPLOAD_URL_ROOT . $this->module . DS . 'pdf', $inputName = 'archive');
                if ($archive == 'error') {
                    $this->redirect(URL_FRIENDLY_BASE . $this->module . '/edit/' . $id . '/upload/pdf');
                }
            }

            $certificado = '';
            if (isset($_FILES) && isset($_FILES['certificado']) && $_FILES['certificado']['size'] > 0) {
                $certificado = Helper::uploadPdf($directoryUpload = UPLOAD_URL_ROOT . $this->module . DS . 'pdf', $inputName = 'certificado');
                if ($certificado == 'error') {
                    $this->redirect(URL_FRIENDLY_BASE . $this->module . '/edit/' . $id . '/upload/pdf');
                }
            }
            /*************************************************************************************************************************************************/
            /* CATEGORIAS */
            /*************************************************************************************************************************************************/

            if (count($_POST["categories"]) > 0) {
                DB::select('DELETE FROM products_categories WHERE products_id = :products_id', [':products_id' => $id]);
            }

            $postProductsCategories = (isset($_POST["categories"])) ? $_POST["categories"] : array();
            $verifiedCategories = array();

            if (count($postProductsCategories)) {
                foreach ($postProductsCategories as $key => $productCategory) {
                    $level_category = self::getLevelCategory($productCategory);
                    if ($level_category > 0) {
                        list($categoryLevel1, $categoryLevel2, $categoryLevel3) = self::getTreeCategories($productCategory, $level_category);

                        if (isset($categoryLevel1) && !empty($categoryLevel1)) {
                            $verifiedCategories[] = $categoryLevel1->id;
                        }
                        if (isset($categoryLevel2) && !empty($categoryLevel2)) {
                            $verifiedCategories[] = $categoryLevel2->id;
                        }
                        if (isset($categoryLevel3) && !empty($categoryLevel3)) {
                            $verifiedCategories[] = $categoryLevel3->id;
                        }
                    }
                }

                array_push($postProductsCategories, $verifiedCategories);
            } else {
                $verifiedCategories = $postProductsCategories;
            }

            foreach ($verifiedCategories as $key => $valor) {
                if (($kt = array_search($valor, $verifiedCategories)) !== false and $key != $kt) {
                    unset($verifiedCategories[$kt]);
                }
            }

            $verifiedCategories = array_merge($verifiedCategories);

            /*************************************************************************************************************************************************/

            $start_date = (isset($_POST['start_date']) && !empty($_POST['start_date'])) ? Helper::dateFormatSystem(Helper::postValue('start_date')) : '';
            $end_date = (isset($_POST['end_date']) && !empty($_POST['end_date'])) ? Helper::dateFormatSystem(Helper::postValue('end_date')) : '';

            $post = array(
                'brands_id' => Helper::postValue('brands_id'),
                'types_id' => Helper::postValue('types_id', 'VENTA'),
                'type' => Helper::postValue('type'),
                'sku' => Helper::postValue('sku'),
                'title' => Helper::postValue('title'),
                'normal_price' => Helper::postValue('normal_price', 0),
                'offer_price' => Helper::postValue('offer_price', 0),
                'stock_control' => Helper::postValue('stock_control', 0),
                'stock' => Helper::postValue('stock', 0),
                'minimum_amount' => Helper::postValue('minimum_amount', 0),
                'discounts' => Helper::postValue('discount', 0),
                'weight' => Helper::postValue('weight', 0),
                'lenght' => Helper::postValue('lenght', 0),
                'width' => Helper::postValue('width', 0),
                'height' => Helper::postValue('height', 0),
                'general_description' => Helper::postValue('general_description'),
                'technical_description' => Helper::postValue('technical_description'),
                'shipping_description' => Helper::postValue('shipping_description'),
                'guarantee_description' => Helper::postValue('guarantee_description'),
                'video_description' => Helper::postValue('video_description'),
                'model' => Helper::postValue('model'),
                'color' => Helper::postValue('color'),
                'talla' => Helper::postValue('talla'),
                'medida' => Helper::postValue('medida'),
                'offer' => Helper::postValue('offer', 0),
                'featured' => Helper::postValue('featured', 0),
                'new' => Helper::postValue('new', 0),
                'a_pedido' => Helper::postValue('a_pedido', 0),
                'visit_number' => Helper::postValue('visits_number', 0),
                'sales_number' => Helper::postValue('sales_number', 0),
                'points' => Helper::postValue('points', 0),
                'shipping_active' => Helper::postValue('shipping_active', 0),
                'office_shipping_active' => Helper::postValue('office_shipping_active', 0),
                'shipping_free' => Helper::postValue('shipping_free', 0),
                'meta_title' => Helper::postValue('meta_title'),
                'meta_description' => Helper::postValue('meta_description'),
                'meta_keyword' => Helper::postValue('meta_keyword'),
                'meta_author' => Helper::postValue('meta_author'),
                'meta_robots' => Helper::postValue('meta_robots'),
                'chilecompracode' => Helper::postValue('chilecompracode'),
                'active' => Helper::postValue('active', 0),
                'author' => Auth::user()->name
            );

            if (!empty($start_date) && $start_date != '0000-00-00') {
                $post['start_date'] = $start_date;
            }

            if (!empty($end_date) && $end_date != '0000-00-00') {
                $post['end_date'] = $end_date;
            }

            if (!empty($archive)) {
                $post['archive'] = $archive;
            }
            if (!empty($certificado)) {
                $post['certificado'] = $certificado;
            }

            if ($update = Products::findOrFail($id)->update($post)) {
                if (count($verifiedCategories) > 0) {
                    foreach ($verifiedCategories as $category_id) {
                        $post = array(
                            'products_id' => $id,
                            'categories_id' => $category_id
                        );
                        ProductsCategories::create($post);
                    }
                }

                DB::select('DELETE FROM products_relations WHERE products_id = :products_id', array(':products_id' => $id));

                $productsRelations = (isset($_POST["relations"]) && !empty($_POST["relations"])) ? $_POST["relations"] : array();
                if (count($productsRelations) > 0) {
                    foreach ($productsRelations as $product_id) {
                        $post = array(
                            'products_id' => $id,
                            'relation_id' => $product_id
                        );
                        ProductsRelations::create($post);
                    }
                }

                if (LOG_GENERATE === true) {
                    Log::create([
                        'id_user' => Helper::sessionSystemValue('id'),
                        'date' => Helper::getDate($hour = false),
                        'hour' => Helper::getHour(),
                        'ip' => Helper::getIP(),
                        'module' => $this->module,
                        'action' => 'ACTUALIZACION',
                        'identifier' => $id,
                        'detail' => 'Actualizó producto "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                    ]);
                }

                session()->flash('error', 'success');
                return redirect()->route('products');
            } else {
                session()->flash('error', 'failure');
                return redirect()->route('products.edit', $id);
            }
        }
    }

    public function delete($id)
    {


        $product = Products::findOrFail($id);

        $archive = $product->archive;
        Helper::deleteArchive(UPLOAD_URL_ROOT . $this->module . DS . 'pdf', $archive);

        $position = $product->position;
        $categories = DB::select('SELECT id FROM ' . $this->module . ' WHERE position >= :position', [':position' => $position]);
        if (count($categories) > 0) {
            foreach ($categories as $product) {
                DB::select('UPDATE ' . $this->module . ' SET position = position - 1 WHERE id = :id', [':id' => $product->id]);
            }
        }

        DB::delete('DELETE FROM products_categories WHERE products_id = :products_id', [':products_id' => $id]);
        DB::delete('DELETE FROM products_galleries WHERE products_id = :products_id', [':products_id' => $id]);
        DB::delete('DELETE FROM products_relations WHERE products_id = :products_id', [':products_id' => $id]);

        if ($delete = Products::findOrFail($id)->delete()) {
            if (LOG_GENERATE === true) {
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó producto "' . $product->title . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('products');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('products');
        }
    }

    public function status()
    {

        $class_status = $text_status = '';
        $status = 0;

        if (isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['id']) && !empty($_POST['id'])) {
            $id = Helper::postValue('id');
            $module_name = Helper::postValue('module_name');

            $product = Products::findOrFail($id);

            $active = ($product->active == 0) ? 1 : 0;

            $post = array(
                'active' => $active,
                'author' => Auth::user()->name
            );

            if ($update = Products::findOrFail($id)->update($post)) {
                $class_status = ($active == 1) ? "badge-success" : "badge-default";
                $text_status = ($active == 1) ? "Activo" : "Inactivo";
                $status = 1;
            }
        }

        echo json_encode(array("status" => $status, "class_status" => $class_status, "text_status" => $text_status));
    }

    public function getProductsRelations()
    {

        $id = Helper::postValue('id', 0);
        $arrayProductsSelected = array();
        $relations_selected = '';

        if (!empty($id) && $id > 0) {
            $productsRelations = DB::select("SELECT products_relations.relation_id AS id FROM products_relations INNER JOIN products ON products_relations.relation_id = products.id WHERE products_id = :products_id", [':products_id' => $id]);

            if (count($productsRelations) > 0) {
                foreach ($productsRelations as $relation) {
                    $arrayProductsSelected[] = $relation->id;
                }
            }

            $products = DB::select("SELECT id, title FROM products WHERE id <> :id", [':id' => $id]);
        } else {
            $products = DB::select("SELECT id, title FROM products");
        }

        if (count($products) > 0) {
            foreach ($products as $product) {
                $selected = (in_array($product->id, $arrayProductsSelected)) ? 'selected="selected"' : '';
                $relations_selected .= '<option value="' . $product->id . '" ' . $selected . '>' . $product->title . '</option>';
            }
        }

        echo json_encode(array("relations_selected" => $relations_selected));
    }

    public function generateBrandsForIndex()
    {
        $content = '';
        $query  = "SELECT id, title FROM brands WHERE active = 1 ORDER BY title ASC";
        $brands = DB::select($query);
        if (count($brands) > 0) {
            foreach ($brands as $brand) {
                $id = $brand->id;
                $title = $brand->title;

                $content .= '<option value="' . $id . '">' . $title . '</option>';
            }
        }

        return $content;
    }

    public function generateCategoriesForIndex($parent, $level, $max, $parent_id = 0, $category_id = 0, &$content)
    {
        $level++;

        $query  = "SELECT id, title FROM categories WHERE active = 1 AND parent_id = {$parent} ";
        $query .= (!empty($category_id)) ? "AND id <> {$category_id} " : "";
        $query .= "ORDER BY title ASC";

        $categories = DB::select($query);
        if (count($categories) > 0) {
            foreach ($categories as $category) {

                $id = $category->id;
                $title = $category->title;

                $text_select = str_pad($title, strlen($title) + ($level - 1), "-", STR_PAD_LEFT);
                $characters = substr($text_select, 0, ($level - 1));
                $text_select = str_replace($characters, $characters . " ", $text_select);

                $selection = ($parent_id == $category->id) ? 'selected="selected"' : '';
                //$disable = (($level - 1) == $max)? 'disabled="disabled"' : '';
                $disable = '';

                $content .= '<option value="' . $id . '" ' . $disable . ' ' . $selection . '>' . $text_select . '</option>';

                if (($level - 1) <= $max) {
                    self::generateCategoriesForIndex($id, $level, $max, $parent_id, $category_id, $content);
                }
            }
        }

        return $content;
    }

    public function generateCategoriesSelect($parent, $level, $products_categories = array(), &$content)
    {
        $level++;

        $query  = "SELECT id, title FROM categories WHERE active = 1 AND parent_id = :parent_id ";
        $query .= "ORDER BY title ASC";

        $categories = DB::select($query, [':parent_id' => $parent]);
        if (count($categories) > 0) {
            foreach ($categories as $category) {

                $id = $category->id;
                $title = $category->title;

                $text_select = str_pad($title, strlen($title) + ($level - 1), "-", STR_PAD_LEFT);
                $characters = substr($text_select, 0, ($level - 1));
                $text_select = str_replace($characters, $characters . " ", $text_select);

                $selection = (in_array($category->id, $products_categories)) ? 'selected="selected"' : '';

                $content .= ($level == 2) ? '<optgroup label="Grupos">' : '';
                $content .= '<option value="' . $id . '" ' . $selection . '>' . $text_select . '</option>';

                if ($level > 1) {
                    self::generateCategoriesSelect($id, $level, $products_categories, $content);
                }

                $content .= ($level == 2) ? '</optgroup>' : '';
            }
        }

        return $content;
    }

    public function generateCategories($parent, $level, $products_categories = array(), &$content)
    {
        $level++;

        $query  = "SELECT id, title FROM categories WHERE active = 1 AND parent_id = :parent_id ";
        $query .= "ORDER BY title ASC";

        $categories = DB::select($query, [':parent_id' => $parent]);
        if (count($categories) > 0) {
            foreach ($categories as $category) {

                $id = $category->id;
                $title = $category->title;

                $selection = (in_array($category->id, $products_categories)) ? 'checked="checked"' : '';

                $content .= '<li>';
                $content .= '<label><span><input type="checkbox" name="products[categories][]" class="validate[minCheckbox[1]]" id="category' . $id . '" ' . $selection . ' value="' . $id . '"></span> ' . $title . '</label>';

                if ($level > 1) {
                    $content .= '<ul class="list-unstyled">';
                    self::generateCategories($id, $level, $products_categories, $content);
                    $content .= '</ul>';
                }

                $content .= '</li>';
            }
        }

        return $content;
    }

    public function getLevelCategory($category_id)
    {
        $query = "SELECT id, title FROM categories WHERE active = 1 AND parent_id = 0 ORDER BY position ASC";
        $categories = DB::select($query);
        if (count($categories) > 0) {
            foreach ($categories as $category) {
                if ($category->id == $category_id) {
                    return 1;
                    break;
                }

                $query = "SELECT id, title FROM categories WHERE active = 1 AND parent_id = :parent_id ORDER BY position ASC";
                $subCategories = DB::select($query, [':parent_id' => $category->id]);
                if (count($subCategories) > 0) {
                    foreach ($subCategories as $subCategory) {
                        if ($subCategory->id == $category_id) {
                            return 2;
                            break;
                        }

                        $query = "SELECT id, title FROM categories WHERE active = 1 AND parent_id = :parent_id ORDER BY position ASC";
                        $subSubCategories = DB::select($query, [':parent_id' => $subCategory->id]);
                        if (count($subSubCategories) > 0) {
                            foreach ($subSubCategories as $subSubCategory) {
                                if ($subSubCategory->id == $category_id) {
                                    return 3;
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function getTreeCategories($category_id, $level)
    {
        if ($level == 3) {
            $categoryLevel3 = Categories::findOrFail($category_id);
            $categoryLevel2 = Categories::findOrFail($categoryLevel3['parent_id']);
            $categoryLevel1 = Categories::findOrFail($categoryLevel2['parent_id']);

            return array($categoryLevel1, $categoryLevel2, $categoryLevel3);
        } else if ($level == 2) {
            $categoryLevel3 = array();
            $categoryLevel2 = Categories::findOrFail($category_id);
            $categoryLevel1 = Categories::findOrFail($categoryLevel2['parent_id']);

            return array($categoryLevel1, $categoryLevel2, $categoryLevel3);
        } else {
            $categoryLevel3 = array();
            $categoryLevel2 = array();
            $categoryLevel1 = Categories::findOrFail($category_id);

            return array($categoryLevel1, $categoryLevel2, $categoryLevel3);
        }
    }

    public function getHeaderXLS()
    {

        $header = array();

        $header[] = "Código Interno";
        $header[] = "Tipo Venta";
        $header[] = "Familia";
        $header[] = "Categoría";
        $header[] = "Sub Categoria";
        $header[] = "Marca";
        $header[] = "Modelo";
        $header[] = "Nombre Producto";
        $header[] = "Precio Normal";
        $header[] = "Precio Oferta";
        $header[] = "Stock";
        $header[] = "Controla Stock";
        $header[] = "Mínimo Cantidad";
        $header[] = "Descuento";
        $header[] = "Descuento Desde";
        $header[] = "Descuento Hasta";
        $header[] = "Peso KG";
        $header[] = "Largo CM";
        $header[] = "Ancho CM";
        $header[] = "Alto CM";
        $header[] = "Descripción General";
        $header[] = "Descripción Técnica";
        $header[] = "Descripción Despachos";
        $header[] = "Descripción Garantía";
        $header[] = "Video";
        $header[] = "Ficha Tecnica";
        $header[] = "Certificado";
        $header[] = "Color";
        $header[] = "Talla";
        $header[] = "Medida";
        $header[] = "Código Chilecompra";
        $header[] = "Nuevo";
        $header[] = "Destacado";
        $header[] = "Oferta";
        $header[] = "Despacho Domicilio";
        $header[] = "Retiro en Tienda";
        $header[] = "Despacho Gratis";
        $header[] = "Visible";

        return $header;
    }

    public function export($all = false)
    {

        $header = array();
        $header = self::getHeaderXLS();

        $objPHPExcel = new PHPExcel();
        HelperExcel::applyBookProperties($objPHPExcel, $this->title);
        HelperExcel::applyZoom($objPHPExcel, 85);
        HelperExcel::applyAutoAdjust($objPHPExcel, 0, count($header));
        HelperExcel::applyFixedRow($objPHPExcel, 0, 2);

        $counter_row = 1;

        $column = 0;
        foreach ($header as $item) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $counter_row, $item);
            $column++;
        }

        HelperExcel::applyCellProperties($objPHPExcel, 0, count($header), $counter_row, true, "FFFFFF", 11, "Calibri", "1f497d", "000000");
        $counter_row++;

        if ($all == 1) {
            $products = DB::select("
            SELECT
            products.id,
            products.type,
            products.sku,
            products.title,
            products.normal_price,
            products.offer_price,
            products.stock_control,
            products.stock,
            products.minimum_amount,
            products.discounts,
            products.start_date,
            products.end_date,
            products.weight,
            products.lenght,
            products.width,
            products.height,
            products.general_description,
            products.technical_description,
            products.shipping_description,
            products.guarantee_description,
            products.video_description,
            products.model,
            products.color,
            products.talla,
            products.medida,
            products.offer,
            products.featured,
            products.new,
            products.shipping_active,
            products.office_shipping_active,
            products.shipping_free,
            products.active,
            products.archive,
            products.certificado,
            products.chilecompracode,
            products_categories.categories_id,
            categories.title AS categories_name,
            brands.title AS brands_name,
            types.title AS types_name
            FROM
            products_categories
            INNER JOIN products ON products_categories.products_id = products.id
            INNER JOIN categories ON products_categories.categories_id = categories.id
            INNER JOIN brands ON products.brands_id = brands.id
            INNER JOIN types ON products.types_id = types.id
            WHERE categories.parent_id = 0
            ORDER BY products.id ASC
            ");

            if (count($products) > 0) {
                foreach ($products as $product) {

                    $active = ($product->active == 1) ? "SI" : "NO";
                    $featured = ($product->featured == 1) ? "SI" : "NO";
                    $offer = ($product->offer == 1) ? "SI" : "NO";
                    $new = ($product->new == 1) ? "SI" : "NO";
                    $shipping_active = ($product->shipping_active == 1) ? "SI" : "NO";
                    $office_shipping_active = ($product->office_shipping_active == 1) ? "SI" : "NO";
                    $shipping_free = ($product->shipping_free == 1) ? "SI" : "NO";
                    $stock_control = ($product->stock_control == 1) ? "SI" : "NO";

                    //----------------------------------------------------------------------------------------------------------------

                    $CATEGORIES_ID_2 = '';
                    $CATEGORIES_NAME_2 = '';

                    $categories = DB::select("
                    SELECT
                    products_categories.categories_id,
                    categories.title
                    FROM
                    products_categories
                    INNER JOIN products ON products_categories.products_id = products.id
                    INNER JOIN categories ON products_categories.categories_id = categories.id
                    WHERE
                    products_categories.products_id = :products_id AND categories.parent_id = :parent_id
                    ORDER BY
                    categories.id ASC LIMIT 1
                    ", array(
                        ':products_id' => $product->id,
                        ':parent_id' => $product->categories_id
                    ));
                    if (count($categories) > 0) {
                        foreach ($categories as $category) {
                            $CATEGORIES_ID_2 = $category->categories_id;
                            $CATEGORIES_NAME_2 = $category->title;
                        }
                    }

                    //----------------------------------------------------------------------------------------------------------------

                    $CATEGORIES_NAME_3 = '';

                    if (!empty($CATEGORIES_ID_2)) {
                        $categories = DB::select("
                        SELECT
                        products_categories.categories_id,
                        categories.title
                        FROM
                        products_categories
                        INNER JOIN products ON products_categories.products_id = products.id
                        INNER JOIN categories ON products_categories.categories_id = categories.id
                        WHERE
                        products_categories.products_id = :products_id AND categories.parent_id = :parent_id
                        ORDER BY
                        categories.id ASC LIMIT 1
                        ", array(
                            ':products_id' => $product->id,
                            ':parent_id' => $CATEGORIES_ID_2
                        ));
                        if (count($categories) > 0) {
                            foreach ($categories as $category) {
                                $CATEGORIES_NAME_3 = $category->title;
                            }
                        }
                    }

                    //----------------------------------------------------------------------------------------------------------------

                    $data = array();

                    $data[] = $product->sku;
                    $data[] = $product->types_name;
                    $data[] = $product->categories_name;
                    $data[] = $CATEGORIES_NAME_2;
                    $data[] = $CATEGORIES_NAME_3;
                    $data[] = $product->brands_name;
                    $data[] = $product->model;
                    $data[] = $product->title;
                    $data[] = $product->normal_price;
                    $data[] = $product->offer_price;
                    $data[] = $product->stock;
                    $data[] = $stock_control;

                    $data[] = $product->minimum_amount;
                    $data[] = $product->discounts;
                    $data[] = $product->start_date;
                    $data[] = $product->end_date;
                    $data[] = $product->weight;
                    $data[] = $product->lenght;
                    $data[] = $product->width;
                    $data[] = $product->height;
                    $data[] = $product->general_description;
                    $data[] = $product->technical_description;
                    $data[] = $product->shipping_description;
                    $data[] = $product->guarantee_description;
                    $data[] = $product->video_description;
                    $data[] = $product->archive;
                    $data[] = $product->certificado;
                    $data[] = $product->color;
                    $data[] = $product->talla;
                    $data[] = $product->medida;
                    $data[] = $product->chilecompracode;
                    $data[] = $new;
                    $data[] = $featured;
                    $data[] = $offer;
                    $data[] = $shipping_active;
                    $data[] = $office_shipping_active;
                    $data[] = $shipping_free;
                    $data[] = $active;

                    $column = 0;
                    foreach ($data as $item) {
                        $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow($column, $counter_row, $item);
                        $column++;
                    }

                    $counter_row++;
                }
            }
        }

        HelperExcel::applyExcelOutput($objPHPExcel, "productos-" . date('YmdHms'));
    }

    public function import()
    {

        $title = 'Importar Productos';
        return view('admvisch.products.import')->with(['title' => $title, 'parent_title' => $this->parent_title, 'module' => $this->module]);
    }

    public function upload()
    {

        $file = '';
        $inputName = 'archivo';
        $fileName = $_FILES[$inputName]['name'];
        $fileType = $_FILES[$inputName]['type'];
        $fileError = $_FILES[$inputName]['error'];
        $fileContent = file_get_contents($_FILES[$inputName]['tmp_name']);

        if ($fileError == UPLOAD_ERR_OK) {

            if (Upload::formIsSubmitted() && isset($_FILES) && $_FILES[$inputName]['size'] > 0) {
                $upload = new Upload($inputName);
                $upload->setDirectory(UPLOAD_URL_ROOT . $this->module)->create(true);

                $upload->addRules([
                    'size' => Helper::maxUploadSize(),
                    'extensions' => 'xlsx',
                ])->customErrorMessages([
                    'size' => 'Sólo puede subir archivos de menos de ' . Helper::uploadSizeUser() . ' de tamaño.',
                    'extensions' => 'Sólo se puede subir archivos xlsx.'
                ]);

                $upload->encryptFileNames(true)->only('xlsx');

                $upload->start();

                if ($upload->unsuccessfulFilesHas()) {
                    if ($upload->displayErrors()) {
                        $fileError = 1;
                        $message = 'Error al procesar el archivo.';
                    }
                }

                if ($upload->successfulFilesHas()) {
                    foreach ($upload->successFiles() as $file) {
                        $fileError = 0;
                        $message = 'Archivo sin errores.';
                        $file = $file->encryptedName;
                    }
                }
            }
        } else {
            switch ($fileError) {
                case UPLOAD_ERR_INI_SIZE:
                    $message = 'Error al intentar subir un archivo que excede el tamaño permitido.';
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $message = 'Error al intentar subir un archivo que excede el tamaño permitido.';
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $message = 'Error: no terminó la acción de subir el archivo.';
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $message = 'Error: ningún archivo fue subido.';
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $message = 'Error: servidor no configurado para carga de archivos.';
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $message = 'Error: posible falla al grabar el archivo.';
                    break;
                case  UPLOAD_ERR_EXTENSION:
                    $message = 'Error: carga de archivo no completada.';
                    break;
                default:
                    $message = 'Error: carga de archivo no completada.';
                    break;
            }
        }

        echo json_encode(array(
            'error' => $fileError,
            'message' => $message,
            'archiveName' => $file
        ));
    }

    public function loadProductsByExcel()
    {

        $fileError = 1;
        $message = "";
        $response_data = $response_data_file = $response = array();
        $number_total = $number_insert = $number_update = $number_delete = $number_archive_found = $number_archive_not_found = 0;

        $file = Helper::postValue('archiveName');

        $route_file = UPLOAD_URL_ROOT . $this->module . DS . $file;

        if (file_exists($route_file)) {
            $xls = new PHPExcel_Reader_Excel2007();
            $xls = $xls->load($route_file);
            $xls->setActiveSheetIndex(0);

            $i = 1;
            $header = array();
            $header = self::getHeaderXLS();
            $headerXLS = array();

            while ($xls->getActiveSheet()->getCell("A" . $i)->getValue() != '') {
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("A" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("B" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("C" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("D" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("E" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("F" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("G" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("H" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("I" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("J" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("K" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("L" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("M" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("N" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("O" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("P" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("Q" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("R" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("S" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("T" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("U" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("V" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("W" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("X" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("Y" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("Z" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("AA" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("AB" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("AC" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("AD" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("AE" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("AF" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("AG" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("AH" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("AI" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("AJ" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("AK" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("AL" . $i)->getValue());

                break;
            }

            $result = array_diff($header, $headerXLS);
            if (count($result) == 0) {
                $i = 2;

                $_array_categories_1 = array();
                $_array_categories_2 = array();
                $_array_categories_3 = array();
                $_array_brands = array();
                $_array_types = array();

                /**
                 * Primero reccorremos el excel para comprobar ingreso de categorias y marcas
                 * */

                while ($xls->getActiveSheet()->getCell("A" . $i)->getValue() != '') {
                    $type_name = trim($xls->getActiveSheet()->getCell("B" . $i)->getValue());
                    $category_name_1 = trim($xls->getActiveSheet()->getCell("C" . $i)->getValue());
                    $category_name_2 = trim($xls->getActiveSheet()->getCell("D" . $i)->getValue());
                    $category_name_3 = trim($xls->getActiveSheet()->getCell("E" . $i)->getValue());
                    $brand_name = trim($xls->getActiveSheet()->getCell("F" . $i)->getValue());

                    $type_name_key = Helper::friendlyUrl($type_name);
                    $category_name_1_key = Helper::friendlyUrl($category_name_1);
                    $category_name_2_key = Helper::friendlyUrl($category_name_2);
                    $category_name_3_key = Helper::friendlyUrl($category_name_3);
                    $brand_name_key = Helper::friendlyUrl($brand_name);

                    if (!empty($type_name_key)) {
                        if (!array_key_exists($type_name_key, $_array_types)) {
                            $types_id = 0;
                            $types = DB::select('SELECT id FROM types WHERE title = :title LIMIT 1', [':title' => $type_name]);
                            if (count($types) > 0) {
                                foreach ($types as $type) {
                                    $types_id = $type->id;
                                    $_array_types[$type_name_key]["id"] = $types_id;
                                }
                            }
                        }
                    }

                    if (!empty($category_name_1)) {
                        if (!array_key_exists($category_name_1, $_array_categories_1)) {
                            $categories_id_1 = 0;
                            $categories = DB::select('SELECT id FROM categories WHERE parent_id = 0 AND title = :title LIMIT 1', [':title' => $category_name_1]);
                            if (count($categories) > 0) {
                                foreach ($categories as $category) {
                                    $categories_id_1 = $category->id;
                                    $_array_categories_1[$category_name_1_key]["id"] = $categories_id_1;
                                }
                            } else {
                                $position = 0;
                                $positions = DB::select('SELECT IFNULL((MAX(position) + 1), 0) AS position FROM categories');
                                if (count($positions) > 0) {
                                    $position = current($positions);
                                    $position = ($position->position == 0) ? 1 : $position->position;
                                }

                                $post = array(
                                    'level' => 1,
                                    'parent_id' => 0,
                                    'title' => $category_name_1,
                                    'slug' => Str::slug($category_name_1, '-'),
                                    'position' => $position,
                                    'active' => 1,
                                    'author' => Helper::sessionSystemValue('user_name')
                                );
                                if ($insert = Categories::create($post)) {
                                    $categories_id_1 = $insert->id;
                                    $_array_categories_1[$category_name_1_key]["id"] = $categories_id_1;
                                }
                            }
                        }
                    }

                    if (!empty($category_name_2)) {
                        $_array_categories_2_current = (isset($_array_categories_2[$category_name_1_key])) ? $_array_categories_2[$category_name_1_key] : array();

                        if (!array_key_exists($category_name_2, $_array_categories_2_current)) {
                            $categories_id_2 = 0;
                            $categories = DB::select('SELECT id FROM categories WHERE parent_id = :parent_id AND title = :title LIMIT 1', [
                                ':parent_id' => $_array_categories_1[$category_name_1_key]['id'],
                                ':title' => $category_name_2
                            ]);
                            if (count($categories) > 0) {
                                foreach ($categories as $category) {
                                    $categories_id_2 = $category->id;
                                    $_array_categories_2[$category_name_1_key][$category_name_2_key]["id"] = $categories_id_2;
                                }
                            } else {
                                $position = 0;
                                $positions = DB::select('SELECT IFNULL((MAX(position) + 1), 0) AS position FROM categories');
                                if (count($positions) > 0) {
                                    $position = current($positions);
                                    $position = ($position->position == 0) ? 1 : $position->position;
                                }

                                $post = array(
                                    'level' => 2,
                                    'parent_id' => $_array_categories_1[$category_name_1_key]['id'],
                                    'title' => $category_name_2,
                                    'slug' => Str::slug($category_name_2, '-'),
                                    'position' => $position,
                                    'active' => 1,
                                    'author' => Helper::sessionSystemValue('user_name')
                                );
                                if ($insert = Categories::create($post)) {
                                    $categories_id_2 = $insert->id;
                                    $_array_categories_2[$category_name_1_key][$category_name_2_key]["id"] = $categories_id_2;
                                }
                            }
                        }
                    }

                    if (!empty($category_name_3)) {
                        $_array_categories_3_current = (isset($_array_categories_3[$category_name_1_key][$category_name_2_key])) ? $_array_categories_3[$category_name_1_key][$category_name_2_key] : array();

                        if (!array_key_exists($category_name_3, $_array_categories_3_current)) {
                            $categories_id_3 = 0;
                            $categories = DB::select('SELECT id FROM categories WHERE parent_id = :parent_id AND title = :title LIMIT 1', [
                                ':parent_id' => $_array_categories_2[$category_name_1_key][$category_name_2_key]['id'],
                                ':title' => $category_name_3
                            ]);
                            if (count($categories) > 0) {
                                foreach ($categories as $category) {
                                    $categories_id_3 = $category->id;
                                    $_array_categories_3[$category_name_1_key][$category_name_2_key][$category_name_3_key]["id"] = $categories_id_3;
                                }
                            } else {
                                $position = 0;
                                $positions = DB::select('SELECT IFNULL((MAX(position) + 1), 0) AS position FROM categories');
                                if (count($positions) > 0) {
                                    $position = current($positions);
                                    $position = ($position->position == 0) ? 1 : $position->position;
                                }

                                $post = array(
                                    'level' => 3,
                                    'parent_id' => $_array_categories_2[$category_name_1_key][$category_name_2_key]['id'],
                                    'title' => $category_name_3,
                                    'slug' => Str::slug($category_name_3, '-'),
                                    'position' => $position,
                                    'active' => 1,
                                    'author' => Helper::sessionSystemValue('user_name')
                                );
                                if ($insert = Categories::create($post)) {
                                    $categories_id_3 = $insert->id;
                                    $_array_categories_3[$category_name_1_key][$category_name_2_key][$category_name_3_key]["id"] = $categories_id_3;
                                }
                            }
                        }
                    }

                    if (!empty($brand_name_key)) {
                        if (!array_key_exists($brand_name_key, $_array_brands)) {
                            $brands_id = 0;
                            $brands = DB::select('SELECT id FROM brands WHERE title = :title LIMIT 1', [':title' => $brand_name]);
                            if (count($brands) > 0) {
                                foreach ($brands as $brand) {
                                    $brands_id = $brand->id;
                                    $_array_brands[$brand_name_key]["id"] = $brands_id;
                                }
                            } else {
                                $position = 0;
                                $positions = DB::select('SELECT IFNULL((MAX(position) + 1), 0) AS position FROM brands');
                                if (count($positions) > 0) {
                                    $position = current($positions);
                                    $position = ($position->position == 0) ? 1 : $position->position;
                                }

                                $post = array(
                                    'title' => $brand_name,
                                    'position' => $position,
                                    'active' => 1,
                                    'author' => Helper::sessionSystemValue('user_name')
                                );
                                if ($insert = Brands::create($post)) {
                                    $brands_id = $insert->id;
                                    $_array_brands[$brand_name_key]["id"] = $brands_id;
                                }
                            }
                        }
                    }

                    $i++;
                }

                $i = 2;

                /**
                 * Segundo reccorremos el excel para comprobar ingreso y/o actualización del producto
                 **/

                while ($xls->getActiveSheet()->getCell("A" . $i)->getValue() != '') {
                    $sku = trim($xls->getActiveSheet()->getCell("A" . $i)->getValue());
                    $type_name = trim($xls->getActiveSheet()->getCell("B" . $i)->getValue());
                    $category_name_1 = trim($xls->getActiveSheet()->getCell("C" . $i)->getValue());
                    $category_name_2 = trim($xls->getActiveSheet()->getCell("D" . $i)->getValue());
                    $category_name_3 = trim($xls->getActiveSheet()->getCell("E" . $i)->getValue());
                    $brand_name = trim($xls->getActiveSheet()->getCell("F" . $i)->getValue());
                    $model = trim($xls->getActiveSheet()->getCell("G" . $i)->getValue());
                    $title = trim($xls->getActiveSheet()->getCell("H" . $i)->getValue());
                    $normal_price = intval(trim($xls->getActiveSheet()->getCell("I" . $i)->getValue()));
                    $offer_price = intval(trim($xls->getActiveSheet()->getCell("J" . $i)->getValue()));
                    $stock = intval(trim($xls->getActiveSheet()->getCell("K" . $i)->getValue()));
                    $stock_control = trim($xls->getActiveSheet()->getCell("L" . $i)->getValue());
                    $minimum_amount = intval(trim($xls->getActiveSheet()->getCell("M" . $i)->getValue()));
                    $discounts = floatval(trim($xls->getActiveSheet()->getCell("N" . $i)->getValue()));
                    $start_date = trim($xls->getActiveSheet()->getCell("O" . $i)->getValue());
                    $end_date = trim($xls->getActiveSheet()->getCell("P" . $i)->getValue());
                    $weight = floatval(trim($xls->getActiveSheet()->getCell("Q" . $i)->getValue()));
                    $lenght = floatval(trim($xls->getActiveSheet()->getCell("R" . $i)->getValue()));
                    $width = floatval(trim($xls->getActiveSheet()->getCell("S" . $i)->getValue()));
                    $height = floatval(trim($xls->getActiveSheet()->getCell("T" . $i)->getValue()));
                    $general_description = trim($xls->getActiveSheet()->getCell("U" . $i)->getValue());
                    $technical_description = trim($xls->getActiveSheet()->getCell("V" . $i)->getValue());
                    $shipping_description = trim($xls->getActiveSheet()->getCell("W" . $i)->getValue());
                    $guarantee_description = trim($xls->getActiveSheet()->getCell("X" . $i)->getValue());
                    $video_description = trim($xls->getActiveSheet()->getCell("Y" . $i)->getValue());
                    $archive = trim($xls->getActiveSheet()->getCell("Z" . $i)->getValue());
                    $certificado = trim($xls->getActiveSheet()->getCell("AA" . $i)->getValue());

                    $color = trim($xls->getActiveSheet()->getCell("AB" . $i)->getValue());
                    $talla = trim($xls->getActiveSheet()->getCell("AC" . $i)->getValue());

                    $medida = trim($xls->getActiveSheet()->getCell("AD" . $i)->getValue());

                    $chilecompracode = trim($xls->getActiveSheet()->getCell("AE" . $i)->getValue());
                    $new = trim($xls->getActiveSheet()->getCell("AF" . $i)->getValue());
                    $featured = trim($xls->getActiveSheet()->getCell("AG" . $i)->getValue());
                    $offer = trim($xls->getActiveSheet()->getCell("AH" . $i)->getValue());
                    $shipping_active = trim($xls->getActiveSheet()->getCell("AI" . $i)->getValue());
                    $office_shipping_active = trim($xls->getActiveSheet()->getCell("AJ" . $i)->getValue());
                    $shipping_free = trim($xls->getActiveSheet()->getCell("AK" . $i)->getValue());
                    $active = trim($xls->getActiveSheet()->getCell("AL" . $i)->getValue());

                    $new = ($new == 'SI') ? 1 : 0;
                    $featured = ($featured == 'SI') ? 1 : 0;
                    $offer = ($offer == 'SI') ? 1 : 0;
                    $shipping_active = ($shipping_active == 'SI') ? 1 : 0;
                    $office_shipping_active = ($office_shipping_active == 'SI') ? 1 : 0;
                    $shipping_free = ($shipping_free == 'SI') ? 1 : 0;
                    $active = ($active == 'SI') ? 1 : 0;
                    $stock_control = ($stock_control == 'SI') ? 1 : 0;

                    $type_name_key = Helper::friendlyUrl($type_name);
                    $category_name_1_key = Helper::friendlyUrl($category_name_1);
                    $category_name_2_key = Helper::friendlyUrl($category_name_2);
                    $category_name_3_key = Helper::friendlyUrl($category_name_3);
                    $brand_name_key = Helper::friendlyUrl($brand_name);

                    $types_id = (!empty($type_name)) ? $_array_types[$type_name_key]["id"] : 0;
                    $categories_id_1 = (!empty($category_name_1)) ? $_array_categories_1[$category_name_1_key]["id"] : 0;
                    $categories_id_2 = (!empty($category_name_2)) ? $_array_categories_2[$category_name_1_key][$category_name_2_key]["id"] : 0;
                    $categories_id_3 = (!empty($category_name_3)) ? $_array_categories_3[$category_name_1_key][$category_name_2_key][$category_name_3_key]["id"] : 0;
                    $brands_id = (!empty($brand_name)) ? $_array_brands[$brand_name_key]["id"] : 0;

                    $response = array(
                        'code' => $sku,
                        'description' => $title,
                        'message' => '',
                        'line' => $i
                    );

                    /**
                     ** Verificación para comprobar si existe el archivo indicado vs archivo en directorio
                     **/

                    if (!empty($archive)) {
                        if (!file_exists(UPLOAD_URL_ROOT . $this->module . DS . 'pdf' . DS . $archive)) {
                            $number_archive_not_found++;
                            $response["error_archive"] = "Nombre de PDF no coincide o no existe en directorio : " . $archive;
                            $response_data_file[] = $response;
                        } else {
                            $number_archive_found++;
                        }
                    }
                    if (!empty($certificado)) {
                        if (!file_exists(UPLOAD_URL_ROOT . $this->module . DS . 'pdf' . DS . $certificado)) {
                            $number_archive_not_found++;
                            $response["error_archive"] = "Nombre de PDF no coincide o no existe en directorio : " . $certificado;
                            $response_data_file[] = $response;
                        } else {
                            $number_archive_found++;
                        }
                    }

                    /**
                     ** Comprobamos si el producto existe a partir de su sku = código grupo
                     **/

                    $products = DB::select('SELECT id FROM ' . $this->module . ' WHERE sku = :sku LIMIT 1', array(':sku' => $sku));
                    if (count($products) > 0) {
                        foreach ($products as $product) {
                            $id = $product->id;

                            $post = array(
                                'types_id' => $types_id,
                                'sku' => $sku,
                                'title' => $title,
                                'slug' => Str::slug($title, '-'),
                                'normal_price' => $normal_price,
                                'offer_price' => $offer_price,
                                'stock_control' => $stock_control,
                                'stock' => $stock,
                                'minimum_amount' => $minimum_amount,
                                'discounts' => $discounts,
                                'start_date' => $start_date,
                                'end_date' => $end_date,
                                'weight' => $weight,
                                'lenght' => $lenght,
                                'width' => $width,
                                'height' => $height,
                                'general_description' => $general_description,
                                'technical_description' => $technical_description,
                                'shipping_description' => $shipping_description,
                                'guarantee_description' => $guarantee_description,
                                'video_description' => $video_description,
                                'model' => $model,
                                'color' => $color,
                                'talla' => $talla,
                                'medida' => $medida,
                                'offer' => $offer,
                                'featured' => $featured,
                                'new' => $new,
                                'shipping_active' => $shipping_active,
                                'office_shipping_active' => $office_shipping_active,
                                'shipping_free' => $shipping_free,
                                'chilecompracode' => $chilecompracode,
                                'active' => $active,
                                'author' => Helper::sessionSystemValue('user_name'),
                                'code_cache' => Helper::getDateInt()
                            );

                            if (!empty($brands_id)) {
                                $post['brands_id'] = $brands_id;
                            }

                            if (!empty($archive)) {
                                $post['archive'] = $archive;
                            }

                            if (!empty($certificado)) {
                                $post['certificado'] = $certificado;
                            }

                            if ($update = Products::findOrFail($id)->update($post)) {

                                $number_update++;

                                if (!empty($categories_id_1)) {
                                    $categories = DB::select('SELECT * FROM products_categories WHERE products_id = :products_id AND categories_id = :categories_id', [':products_id' => $id, ':categories_id' => $categories_id_1]);
                                    if (count($categories) == 0) {
                                        $post = array(
                                            'products_id' => $id,
                                            'categories_id' => $categories_id_1
                                        );
                                        ProductsCategories::create($post);
                                    }
                                }
                                if (!empty($categories_id_2)) {
                                    $categories = DB::select('SELECT * FROM products_categories WHERE products_id = :products_id AND categories_id = :categories_id', [':products_id' => $id, ':categories_id' => $categories_id_2]);
                                    if (count($categories) == 0) {
                                        $post = array(
                                            'products_id' => $id,
                                            'categories_id' => $categories_id_2
                                        );
                                        ProductsCategories::create($post);
                                    }
                                }
                                if (!empty($categories_id_3)) {
                                    $categories = DB::select('SELECT * FROM products_categories WHERE products_id = :products_id AND categories_id = :categories_id', [':products_id' => $id, ':categories_id' => $categories_id_3]);
                                    if (count($categories) == 0) {
                                        $post = array(
                                            'products_id' => $id,
                                            'categories_id' => $categories_id_3
                                        );
                                        ProductsCategories::create($post);
                                    }
                                }
                            } else {
                                $response["message"] = 'Error al actualizar registro.';
                                $response_data[] = $response;
                            }
                        }
                    } else {

                        $position = 0;
                        $positions = DB::select('SELECT IFNULL((MAX(position) + 1), 0) AS position FROM products');
                        if (count($positions) > 0) {
                            $position = current($positions);
                            $position = ($position->position == 0) ? 1 : $position->position;
                        }

                        $post = array(
                            'types_id' => $types_id,
                            'sku' => $sku,
                            'title' => $title,
                            'slug' => Str::slug($title, '-'),
                            'normal_price' => $normal_price,
                            'offer_price' => $offer_price,
                            'stock_control' => $stock_control,
                            'stock' => $stock,
                            'minimum_amount' => $minimum_amount,
                            'discounts' => $discounts,
                            'start_date' => $start_date,
                            'end_date' => $end_date,
                            'weight' => $weight,
                            'lenght' => $lenght,
                            'width' => $width,
                            'height' => $height,
                            'general_description' => $general_description,
                            'technical_description' => $technical_description,
                            'shipping_description' => $shipping_description,
                            'guarantee_description' => $guarantee_description,
                            'video_description' => $video_description,
                            'model' => $model,
                            'color' => $color,
                            'talla' => $talla,
                            'medida' => $medida,
                            'offer' => $offer,
                            'featured' => $featured,
                            'new' => $new,
                            'shipping_active' => $shipping_active,
                            'office_shipping_active' => $office_shipping_active,
                            'shipping_free' => $shipping_free,
                            'chilecompracode' => $chilecompracode,
                            'archive' => $archive,
                            'certificado' => $certificado,
                            'active' => $active,
                            'visit_number' => 0,
                            'sales_number' => 0,
                            'points' => 0,
                            'position' => $position,
                            'author' => Helper::sessionSystemValue('user_name'),
                            'code_cache' => Helper::getDateInt()
                        );

                        if (!empty($brands_id)) {
                            $post['brands_id'] = $brands_id;
                        }

                        if ($insert = Products::create($post)) {

                            $number_insert++;

                            $id = $insert->id;

                            if (!empty($categories_id_1)) {
                                $categories = DB::select('SELECT * FROM products_categories WHERE products_id = :products_id AND categories_id = :categories_id', [':products_id' => $id, ':categories_id' => $categories_id_1]);
                                if (count($categories) == 0) {
                                    $post = array(
                                        'products_id' => $id,
                                        'categories_id' => $categories_id_1
                                    );
                                    ProductsCategories::create($post);
                                }
                            }
                            if (!empty($categories_id_2)) {
                                $categories = DB::select('SELECT * FROM products_categories WHERE products_id = :products_id AND categories_id = :categories_id', [':products_id' => $id, ':categories_id' => $categories_id_2]);
                                if (count($categories) == 0) {
                                    $post = array(
                                        'products_id' => $id,
                                        'categories_id' => $categories_id_2
                                    );
                                    ProductsCategories::create($post);
                                }
                            }
                            if (!empty($categories_id_3)) {
                                $categories = DB::select('SELECT * FROM products_categories WHERE products_id = :products_id AND categories_id = :categories_id', [':products_id' => $id, ':categories_id' => $categories_id_3]);
                                if (count($categories) == 0) {
                                    $post = array(
                                        'products_id' => $id,
                                        'categories_id' => $categories_id_3
                                    );
                                    ProductsCategories::create($post);
                                }
                            }
                        } else {
                            $response["message"] = 'Error al ingresar registro.';
                            $response_data[] = $response;
                        }
                    }

                    $i++;
                    $number_total++;
                }

                $fileError = 0;
            } else {
                $message = 'Error: el archivo no corresponde al formato establecido.';
                $fileError = 1;
            }

            /********************************************************************************************************/

            Helper::deleteArchive(UPLOAD_URL_ROOT . $this->module, $file);
        } else {
            $message = 'Error: no se ha logrado encontrar o leer el archivo de carga.';
        }

        echo json_encode(array(
            'error' => $fileError,
            'message' => $message,
            'response_data' => $response_data,
            'response_data_file' => $response_data_file,
            'number_total' => Helper::formatDecimals($number_total, 0),
            'number_insert' => Helper::formatDecimals($number_insert, 0),
            'number_update' => Helper::formatDecimals($number_update, 0),
            'number_delete' => Helper::formatDecimals($number_delete, 0),
            'number_archive_found' => Helper::formatDecimals($number_archive_found, 0),
            'number_archive_not_found' => Helper::formatDecimals($number_archive_not_found, 0),
        ));
    }

    public function getHeaderGalleriesXLS()
    {

        $header = array();

        $header[] = "Código Interno";
        $header[] = "Imagen";

        return $header;
    }

    public function exportGalleries($all = false)
    {

        $header = array();
        $header = self::getHeaderGalleriesXLS();

        $objPHPExcel = new PHPExcel();
        HelperExcel::applyBookProperties($objPHPExcel, $this->title);
        HelperExcel::applyZoom($objPHPExcel, 85);
        HelperExcel::applyAutoAdjust($objPHPExcel, 0, count($header));
        HelperExcel::applyFixedRow($objPHPExcel, 0, 2);

        $counter_row = 1;

        $column = 0;
        foreach ($header as $item) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $counter_row, $item);
            $column++;
        }

        HelperExcel::applyCellProperties($objPHPExcel, 0, count($header), $counter_row, true, "FFFFFF", 11, "Calibri", "1f497d", "000000");
        $counter_row++;

        if ($all == 1) {
            $products = DB::select("
            SELECT
            products.sku,
            products_galleries.image
            FROM
            products_galleries
            INNER JOIN products ON products_galleries.products_id = products.id
            ORDER BY products.id ASC, products_galleries.position ASC
            ");

            if (count($products) > 0) {
                foreach ($products as $product) {

                    $data = array();

                    $data[] = $product->sku;
                    $data[] = $product->image;

                    $column = 0;
                    foreach ($data as $item) {
                        $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow($column, $counter_row, $item);
                        $column++;
                    }

                    $counter_row++;
                }
            }
        }

        HelperExcel::applyExcelOutput($objPHPExcel, "productos-galerias-" . date('YmdHms'));
    }

    public function importGalleries()
    {


        $title = 'Importar Galería';

        return view('admvisch.products.import-galleries')->with(['title' => $title, 'parent_title' => $this->parent_title, 'module' => $this->module]);
    }

    public function uploadGalleries()
    {

        $file = '';
        $inputName = 'archivo';
        $fileName = $_FILES[$inputName]['name'];
        $fileType = $_FILES[$inputName]['type'];
        $fileError = $_FILES[$inputName]['error'];
        $fileContent = file_get_contents($_FILES[$inputName]['tmp_name']);

        if ($fileError == UPLOAD_ERR_OK) {

            if (Upload::formIsSubmitted() && isset($_FILES) && $_FILES[$inputName]['size'] > 0) {
                $upload = new Upload($inputName);
                $upload->setDirectory(UPLOAD_URL_ROOT . 'productsGalleries')->create(true);

                $upload->addRules([
                    'size' => Helper::maxUploadSize(),
                    'extensions' => 'xlsx',
                ])->customErrorMessages([
                    'size' => 'Sólo puede subir archivos de menos de ' . Helper::uploadSizeUser() . ' de tamaño.',
                    'extensions' => 'Sólo se puede subir archivos xlsx.'
                ]);

                $upload->encryptFileNames(true)->only('xlsx');

                $upload->start();

                if ($upload->unsuccessfulFilesHas()) {
                    if ($upload->displayErrors()) {
                        $fileError = 1;
                        $message = 'Error al procesar el archivo.';
                    }
                }

                if ($upload->successfulFilesHas()) {
                    foreach ($upload->successFiles() as $file) {
                        $fileError = 0;
                        $message = 'Archivo sin errores.';
                        $file = $file->encryptedName;
                    }
                }
            }
        } else {
            switch ($fileError) {
                case UPLOAD_ERR_INI_SIZE:
                    $message = 'Error al intentar subir un archivo que excede el tamaño permitido.';
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $message = 'Error al intentar subir un archivo que excede el tamaño permitido.';
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $message = 'Error: no terminó la acción de subir el archivo.';
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $message = 'Error: ningún archivo fue subido.';
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $message = 'Error: servidor no configurado para carga de archivos.';
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $message = 'Error: posible falla al grabar el archivo.';
                    break;
                case  UPLOAD_ERR_EXTENSION:
                    $message = 'Error: carga de archivo no completada.';
                    break;
                default:
                    $message = 'Error: carga de archivo no completada.';
                    break;
            }
        }

        echo json_encode(array(
            'error' => $fileError,
            'message' => $message,
            'archiveName' => $file
        ));
    }

    public function loadGalleriesByExcel()
    {

        $fileError = 1;
        $message = "";
        $response_data = $response_data_file = $response = array();
        $number_total = $number_insert = $number_update = $number_delete = $number_archive_found = $number_archive_not_found = 0;

        $file = Helper::postValue('archiveName');

        $route_file = UPLOAD_URL_ROOT . 'productsGalleries' . DS . $file;

        if (file_exists($route_file)) {
            $xls = new PHPExcel_Reader_Excel2007();
            $xls = $xls->load($route_file);
            $xls->setActiveSheetIndex(0);

            $i = 1;
            $header = array();
            $header = self::getHeaderGalleriesXLS();
            $headerXLS = array();

            while ($xls->getActiveSheet()->getCell("A" . $i)->getValue() != '') {
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("A" . $i)->getValue());
                $headerXLS[] = trim($xls->getActiveSheet()->getCell("B" . $i)->getValue());

                break;
            }

            $result = array_diff($header, $headerXLS);
            if (count($result) == 0) {
                $i = 2;

                /**
                 * Reccorremos el excel para comprobar ingreso y/o actualización del producto
                 **/

                while ($xls->getActiveSheet()->getCell("A" . $i)->getValue() != '') {
                    $sku = trim($xls->getActiveSheet()->getCell("A" . $i)->getValue());
                    $archive = trim($xls->getActiveSheet()->getCell("B" . $i)->getValue());

                    $response = array(
                        'code' => $sku,
                        'description' => $archive,
                        'message' => '',
                        'line' => $i
                    );

                    /**
                     ** Verificación para comprobar si existe el archivo indicado vs archivo en directorio
                     **/

                    if (!empty($archive)) {
                        if (!file_exists(UPLOAD_URL_ROOT . 'productsGalleries' . DS . 'img' . DS . $archive)) {
                            $number_archive_not_found++;
                            $response["error_archive"] = "Nombre de JPG no coincide o no existe en directorio : " . $archive;
                            $response_data_file[] = $response;
                        } else {
                            $number_archive_found++;
                        }
                    }

                    /**
                     ** Comprobamos si el producto existe a partir de su sku = código grupo
                     **/

                    $products = DB::select('SELECT id FROM ' . $this->module . ' WHERE sku = :sku LIMIT 1', [':sku' => $sku]);
                    if (count($products) > 0) {
                        foreach ($products as $product) {
                            $products_id = $product->id;

                            $galleries = DB::select('SELECT id FROM products_galleries WHERE products_id = :products_id AND image = :image LIMIT 1', [':products_id' => $products_id, ':image' => $archive]);
                            if (count($galleries) > 0) {
                                foreach ($galleries as $gallery) {
                                    $id = $gallery->id;

                                    $post = array(
                                        'image' => $archive,
                                        'active' => 1,
                                        'author' => Helper::sessionSystemValue('user_name'),
                                    );

                                    if ($update = ProductsGalleries::findOrFail($id)->update($post)) {

                                        $number_update++;
                                    } else {
                                        $response["message"] = 'Error al actualizar registro.';
                                        $response_data[] = $response;
                                    }
                                }
                            } else {
                                $position = 0;
                                $positions = DB::select('SELECT IFNULL((MAX(position) + 1), 0) AS position FROM products_galleries WHERE products_id = :products_id', array(':products_id' => $products_id));
                                if (count($positions) > 0) {
                                    $position = current($positions);
                                    $position = ($position->position == 0) ? 1 : $position->position;
                                }

                                $post = array(
                                    'products_id' => $products_id,
                                    'image' => $archive,
                                    'position' => $position,
                                    'active' => 1,
                                    'author' => Helper::sessionSystemValue('user_name'),
                                );
                                if ($insert = ProductsGalleries::create($post)) {

                                    $number_insert++;
                                } else {
                                    $response["message"] = 'Error al ingresar registro.';
                                    $response_data[] = $response;
                                }
                            }
                        }
                    } else {
                        $response["message"] = "Error: Código Interno no existe como producto.";
                        $response_data[] = $response;
                    }

                    $i++;
                    $number_total++;
                }

                $fileError = 0;
            } else {
                $message = 'Error: el archivo no corresponde al formato establecido.';
                $fileError = 1;
            }

            /********************************************************************************************************/

            Helper::deleteArchive(UPLOAD_URL_ROOT . 'productsGalleries', $file);
        } else {
            $message = 'Error: no se ha logrado encontrar o leer el archivo de carga.';
        }

        echo json_encode(array(
            'error' => $fileError,
            'message' => $message,
            'response_data' => $response_data,
            'response_data_file' => $response_data_file,
            'number_total' => Helper::formatDecimals($number_total, 0),
            'number_insert' => Helper::formatDecimals($number_insert, 0),
            'number_update' => Helper::formatDecimals($number_update, 0),
            'number_delete' => Helper::formatDecimals($number_delete, 0),
            'number_archive_found' => Helper::formatDecimals($number_archive_found, 0),
            'number_archive_not_found' => Helper::formatDecimals($number_archive_not_found, 0),
        ));
    }
}
