<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Application\Helper;
use App\Models\Categories;
use Illuminate\Http\Request;
use App\Models\CategoriesProducts;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{



    private $title = 'Categorías';
    private $parent_title = 'Tienda Virtual';
    private $module = 'categories';
    private $image_description = '150 x 100';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {



        $categories = DB::select('
        SELECT
        categories.id,
        categories.parent_id,
        categories.title,
        categories.description,
        categories.main_image,
        categories.secondary_image,
        categories.discount,
        categories.start_date,
        categories.end_date,
        categories.position,
        categories.featured,
        categories.active,
        categories.created_at,
        categories.updated_at,
        categories.author,
        CASE
            WHEN parent_id > 0 THEN
                CASE
                    WHEN (SELECT c.title FROM categories AS c WHERE c.id = categories.parent_id AND c.parent_id = 0) IS NULL THEN
                    (SELECT d.title FROM categories AS d WHERE d.id = (SELECT c.parent_id FROM categories AS c WHERE c.id = categories.parent_id AND c.parent_id <> 0))
                    ELSE (SELECT c.title FROM categories AS c WHERE c.id = categories.parent_id AND c.parent_id = 0)
                END
        END AS category_title_level_2,
        (SELECT c.title FROM categories AS c WHERE c.id = categories.parent_id AND c.parent_id <> 0) AS category_title_level_3
        FROM
        categories
        ORDER BY categories.id DESC
        ');


        return view('admvisch.categories.index')->with(['title' => $this->title, 'module' => $this->module, 'parent_title' => $this->parent_title, 'categories' => $categories]);
    }

    public function enter()
    {
        $content = '';
        $contentCategories = $this->generateCategories(0, 1, 3, 0, 0, $content);

        return view('admvisch.categories.enter')->with(['title' => $this->title, 'module' => $this->module, 'parent_title' => $this->parent_title, 'categories' => $contentCategories, 'main_image_description' => $this->image_description]);
    }

    public function insert(Request $request)
    {

        if ($request->file('main_image')) {
            $image = $request->file('main_image')->store('public/imagenes');
            $url = Storage::url($image);
        } else {
            $url = '';
        }

        $parent_id = (isset($_POST['parent_id']) && !empty($_POST['parent_id'])) ? $_POST['parent_id'] : 0;
        $level = (!empty($parent_id)) ? self::getCategoryLevel($parent_id) + 1 : 1;

        $categories = Categories::where(['title' => Helper::postValue('title')])->where('parent_id', $parent_id)->get()->count();
        $author = (isset($_POST['author']) && !empty($_POST['author'])) ? Helper::postValue('author') : '';

        if ($categories > 0) {
            session()->flash('error', 'duplicate');
            return redirect()->route('categories.enter');
        } else {
            $position = 0;
            $positions = DB::table($this->module)->max('position');
            $position = $positions + 1;

            $_POST['discount'] = 0;
            $_POST['start_date'] = '';
            $_POST['end_date'] = '';

            $start_date = (isset($_POST['start_date']) && !empty($_POST['start_date'])) ? Helper::dateFormatSystem(Helper::postValue('start_date')) : '';
            $end_date = (isset($_POST['end_date']) && !empty($_POST['end_date'])) ? Helper::dateFormatSystem(Helper::postValue('end_date')) : '';

            $post = array(
                'level' => $level,
                'slug' => Str::slug(Helper::postValue('title'), '-'),
                'parent_id' => $parent_id,
                'title' => Helper::postValue('title'),
                'description' => Helper::postValue('description'),
                'main_image' => $url,
                'secondary_image' => '',
                'offer_image' => '',
                'discount' => Helper::postValue('discount', 0),
                'position' => $position,
                'featured' => Helper::postValue('featured', 0),
                'active' => Helper::postValue('active', 0),
                'author' => $author,
            );

            if (!empty($start_date) && $start_date != '0000-00-00') {
                $post['start_date'] = $start_date;
            }

            if (!empty($end_date) && $end_date != '0000-00-00') {
                $post['end_date'] = $end_date;
            }

            if ($insert = Categories::create($post)) {
                $id = $insert->id;

                if (LOG_GENERATE === true) {
                    Log::create([
                        'id_user' => Helper::sessionSystemValue('id'),
                        'date' => Helper::getDate($hour = false),
                        'hour' => Helper::getHour(),
                        'ip' => Helper::getIP(),
                        'module' => $this->module,
                        'action' => 'INGRESO',
                        'identifier' => $id,
                        'detail' => 'Ingresó nueva categoria "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                    ]);
                }

                session()->flash('error', 'success');
                return redirect()->route('categories');
            } else {
                session()->flash('error', 'failure');
                return redirect()->route('categories.enter');
            }
        }
    }

    public function edit($id)
    {

        $category = Categories::findOrFail($id);

        $category->start_date = ($category->start_date == '0000-00-00' || $category->start_date == '') ? '' : Helper::dateFormatUser($category->start_date, false);
        $category->end_date = ($category->end_date == '0000-00-00'  || $category->end_date == '') ? '' : Helper::dateFormatUser($category->end_date, false);
        $content = '';
        $contentCategories = $this->generateCategories(0, 1, 3, $category->parent_id, $category->id, $content);

        return view('admvisch.categories.edit')->with(['title' => $this->title, 'module' => $this->module, 'parent_title' => $this->parent_title, 'categories' => $contentCategories, 'category' => $category]);
    }

    public function update()
    {

        $id = Helper::postValue('id');
        $parent_id = (isset($_POST['parent_id']) && !empty($_POST['parent_id'])) ? $_POST['parent_id'] : 0;
        $level = (!empty($parent_id)) ? self::getCategoryLevel($parent_id) + 1 : 1;
        $author = (isset($_POST['author']) && !empty($_POST['author'])) ? Helper::postValue('author') : '';

        $categories = Categories::where('title', Helper::postValue('title'))->where('parent_id', $parent_id)->where('id', '<>', $id)->get()->count();

        if ($categories > 0) {
            session()->flash('error', 'duplicate');
            return redirect()->route('categories.edit', $id);
        } else {

            $main_image = $secondary_image = $offer_image = '';


            if (!empty($parent_id)) {
                $_POST['discount'] = 0;
                $_POST['start_date'] = '';
                $_POST['end_date'] = '';
            }

            $start_date = (isset($_POST['start_date']) && !empty($_POST['start_date'])) ? Helper::dateFormatSystem(Helper::postValue('start_date')) : '';
            $end_date = (isset($_POST['end_date']) && !empty($_POST['end_date'])) ? Helper::dateFormatSystem(Helper::postValue('end_date')) : '';

            $post = array(
                'level' => $level,
                'parent_id' => $parent_id,
                'title' => Helper::postValue('title'),
                'description' => Helper::postValue('description'),
                'discount' => Helper::postValue('discount', 0),
                'featured' => Helper::postValue('featured', 0),
                'active' => Helper::postValue('active', 0),
                'author' => $author
            );

            if (!empty($start_date) && $start_date != '0000-00-00') {
                $post['start_date'] = $start_date;
            }

            if (!empty($end_date) && $end_date != '0000-00-00') {
                $post['end_date'] = $end_date;
            }

            if (!empty($main_image)) {
                $post['main_image'] = $main_image;
            }

            if (!empty($secondary_image)) {
                $post['secondary_image'] = $secondary_image;
            }

            if (!empty($offer_image)) {
                $post['offer_image'] = $offer_image;
            }

            if ($update = Categories::findOrFail($id)->update($post)) {
                DB::select('DELETE FROM categories_products WHERE categories_id = :categories_id', array(':categories_id' => $id));

                $productsRelations = (isset($_POST["relations"]) && !empty($_POST["relations"])) ? $_POST["relations"] : array();
                if (count($productsRelations) > 0) {
                    foreach ($productsRelations as $product_id) {
                        $post = array(
                            'categories_id' => $id,
                            'products_id' => $product_id
                        );
                        CategoriesProducts::create($post);
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
                        'detail' => 'Actualizó marca "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                    ]);
                }

                session()->flash('error', 'success');
                return redirect()->route('categories');
            } else {
                session()->flash('error', 'failure');
                return redirect()->route('categories.edit', $id);
            }
        }
    }

    public function delete($id)
    {

        $category = Categories::findOrFail($id);

        $position = $category->position;
        $categories = DB::select('SELECT id FROM ' . $this->module . ' WHERE position >= :position', [':position' => $position]);
        if (count($categories) > 0) {
            foreach ($categories as $category) {
                DB::select('UPDATE ' . $this->module . ' SET position = position - 1 WHERE id = :id', [':id' => $category->id]);
            }
        }

        if ($delete = Categories::findOrFail($id)->delete()) {

            DB::select('DELETE FROM categories_products WHERE categories_id = :categories_id', [':categories_id' => $id]);

            if (LOG_GENERATE === true) {
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó marca "' . $category->title . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('categories');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('categories');
        }
    }

    public function status()
    {

        $class_status = $text_status = '';
        $status = 0;

        if (isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['id']) && !empty($_POST['id'])) {
            $id = Helper::postValue('id');
            $module_name = Helper::postValue('module_name');

            $category = Categories::findOrFail($id);

            $active = ($category->active == 0) ? 1 : 0;

            $post = array(
                'active' => $active,
                'author' => Auth::user()->name
            );

            if ($update = Categories::findOrFail($id)->update($post)) {
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
            $productsRelations = DB::select("SELECT categories_products.products_id AS id FROM categories_products INNER JOIN products ON categories_products.products_id = products.id WHERE categories_id = :categories_id", [':categories_id' => $id]);

            if (count($productsRelations) > 0) {
                foreach ($productsRelations as $relation) {
                    $arrayProductsSelected[] = $relation->id;
                }
            }

            $products = DB::select("
            SELECT
            products.id,
            products.title,
            products.sku
            FROM
            products
            INNER JOIN products_categories ON products_categories.products_id = products.id
            INNER JOIN categories ON products_categories.categories_id = categories.id
            WHERE
            categories.id = :id
            GROUP BY
            products.id,
            products.title,
            products.sku
            ORDER BY
            products.title ASC", [':id' => $id]);
        } else {
            $products = array();
        }

        if (count($products) > 0) {
            foreach ($products as $product) {
                $selected = (in_array($product->id, $arrayProductsSelected)) ? 'selected="selected"' : '';
                $relations_selected .= '<option value="' . $product->id . '" ' . $selected . '>' . $product->title . '</option>';
            }
        }

        echo json_encode(array("relations_selected" => $relations_selected));
    }

    public function generateCategories($parent, $level, $max, $parent_id = 0, $category_id = 0, &$content)
    {
        $level++;

        $query  = "SELECT id, title FROM " . $this->module . " WHERE active = 1 AND parent_id = {$parent} ";
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
                $disable = (($level - 1) == $max) ? 'disabled="disabled"' : '';

                $content .= '<option value="' . $id . '" ' . $disable . ' ' . $selection . '>' . $text_select . '</option>';

                if (($level - 1) <= $max) {
                    self::generateCategories($id, $level, $max, $parent_id, $category_id, $content);
                }
            }
        }

        return $content;
    }

    public function getLevelCategory($parent, $level, $max, $parent_id = 0, $category_id = 0, $search_id, &$content)
    {
        $level++;

        $query  = "SELECT id FROM " . $this->module . " WHERE active = 1 AND parent_id = {$parent} ";
        $query .= (!empty($category_id)) ? "AND id <> {$category_id} " : "";
        $query .= "ORDER BY title ASC";

        $categories = DB::select($query);
        if (count($categories) > 0) {
            foreach ($categories as $category) {

                $id = $category->id;

                if ($search_id == $id) {
                    $level_found = ($level - 1);
                    $content .= $level_found;
                }

                if (($level - 1) <= $max) {
                    self::getLevelCategory($id, $level, $max, $parent_id, $category_id, $search_id, $content);
                }
            }
        }

        return $content;
    }

    public function generateArrayCategories()
    {

        $categoriesMenu = array();

        $query = "SELECT id, parent_id, title FROM categories WHERE active = 1 AND parent_id = 0 ORDER BY title ASC";
        $families = DB::select($query);

        $counter = 0;
        if (count($families) > 0) {
            foreach ($families as $family) {
                $categoriesMenu[$counter] = array(
                    'id' => $family->id,
                    'title' => $family->title,
                    'level' => $counter,
                    'categories' => array()
                );

                $query = "SELECT id, parent_id, title FROM categories WHERE active = 1 AND parent_id = {$family->id} ORDER BY title ASC";
                $categories = DB::select($query);

                if (count($categories) > 0) {
                    $counter2 = 0;
                    foreach ($categories as $category) {
                        $categoriesMenu[$counter]['categories'][$counter2] = array(
                            'id' => $category->id,
                            'title' => $category->title,
                            'level' => $counter2,
                            'subcategories' => array()
                        );

                        $query = "SELECT id, parent_id, title FROM categories WHERE active = 1 AND parent_id = {$category->id} ORDER BY title ASC";
                        $subcategories = DB::select($query);

                        if (count($subcategories) > 0) {
                            $counter3 = 0;
                            foreach ($subcategories as $subcategory) {
                                $categoriesMenu[$counter]['categories'][$counter2]['subcategories'][] = array(
                                    'id' => $subcategory->id,
                                    'title' => $subcategory->title,
                                    'level' => $counter3,
                                );

                                $counter3++;
                            }
                        }
                        $counter2++;
                    }
                }
                $counter++;
            }
        }

        return $categoriesMenu;
    }

    public function getCategoriesIdLevel()
    {

        $categoriesMenu = self::generateArrayCategories();
        $categoriesMenuWithLevels = array();

        if (count($categoriesMenu) > 0) {
            foreach ($categoriesMenu as $keyFamily => $family) {
                $categoriesMenuWithLevels[] = array("id" => $family["id"], "level" => 1);
                if (count($family['categories']) > 0) {
                    foreach ($family['categories'] as $keyCategory => $category) {
                        $categoriesMenuWithLevels[] = array("id" => $category["id"], "level" => 2);
                        if (count($category['subcategories']) > 0) {
                            foreach ($category['subcategories'] as $keySubCategory => $subcategory) {
                                $categoriesMenuWithLevels[] = array("id" => $subcategory["id"], "level" => 3);
                            }
                        }
                    }
                }
            }
        }

        return $categoriesMenuWithLevels;
    }

    public function getCategoryLevel($id_category)
    {
        $categoriesMenu = self::getCategoriesIdLevel();

        if (count($categoriesMenu) > 0) {
            for ($i = 0; $i < sizeof($categoriesMenu); $i++) {
                if ($categoriesMenu[$i]["id"] == $id_category) {
                    return $categoriesMenu[$i]["level"];
                    break;
                }
            }
        }
    }
}
