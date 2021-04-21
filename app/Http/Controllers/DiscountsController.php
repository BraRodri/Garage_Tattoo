<?php

namespace App\Http\Controllers;

use App\Models\Brands;
use App\Models\Categories;
use App\Models\Clients;
use App\Models\Configurations;
use App\Models\Discounts;
use App\Models\DiscountsBrands;
use App\Models\DiscountsCategories;
use App\Models\DiscountsClients;
use App\Models\DiscountsProducts;
use App\Models\Log;
use App\Models\Metadata;
use App\Models\Products;
use Application\Helper;
use Illuminate\Http\Request;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Libraries\Phpmailer\PHPMailer;

class DiscountsController extends Controller
{
    private $title = 'Cupón de Descuento';
    private $module = 'discounts';
    private  $types = [
        'Sale' => 'Venta',
        'Clients' => 'Clientes',
        'Categories' => 'Categorías',
        'Brands' => 'Marcas',
        'Products' => 'Productos',
    ];
    private $image_description = '1900 x 480';


    public function index(){


        $discounts = Discounts::orderByDesc('id')->get();

        return view('admvisch.discounts.index')->with(['title'=>$this->title,'module'=>$this->module, 'discounts'=>$discounts, 'types'=>$this->types, 'image_description'=>$this->image_description]);
    }

    public function enter(){



        return view('admvisch.discounts.enter')->with(['title'=>$this->title,'module'=>$this->module, 'types'=>$this->types, 'image_description'=>$this->image_description]);
    }

    public function insert(){

       

        $discounts = Discounts::where(['code' => Helper::postValue('code')])->get()->count();
        $author=(isset($_POST['author']) && !empty($_POST['author']))? Helper::postValue('author') : '';

        if($discounts > 0){
            session()->flash('error', 'duplicate');
            return redirect()->route('discounts.enter');
        } else {

            $type = Helper::postValue('type', 'Sale');
            $relations = (isset($_POST["relations"]) && !empty($_POST["relations"]))? $_POST["relations"] : [];

            if(($type == 'Clients' || $type == 'Categories' || $type == 'Brands' || $type == 'Products') && count($relations) == 0){
                session()->flash('error', 'selected');
            return redirect()->route('discounts.enter');
            }

            $discount_percentage = Helper::postValue('discount_percentage', 0);
            $discount_percentage = (is_float($discount_percentage))? $discount_percentage : floatval($discount_percentage);
            $discount_percentage = ($discount_percentage > 100)? 100 : $discount_percentage;

            $discount = Helper::postValue('discount', 0);
            $discount = (is_int($discount))? $discount : intval($discount);

            if($discount == 0 && $discount_percentage == 0){
                session()->flash('error', 'discount');
            return redirect()->route('discounts.enter');
            }

            $start_date = (isset($_POST['start_date']) && !empty($_POST['start_date']))? Helper::dateFormatSystem(Helper::postValue('start_date')) : '';
            $end_date = (isset($_POST['end_date']) && !empty($_POST['end_date']))? Helper::dateFormatSystem(Helper::postValue('end_date')) : '';

            $send = Helper::postValue('send', 0);
            $active = Helper::postValue('active', 0);

            $post = array(
                'title' => Helper::postValue('title'),
                'description' => Helper::postValue('description'),
                'code' => Helper::postValue('code'),
                'discount_percentage' => $discount_percentage,
                'discount' => $discount,
                'restrictions' => Helper::postValue('restrictions'),
                'type' => $type,
                'send' => $send,
                'active' => $active,
                'author' => $author
            );

            if(!empty($start_date) && $start_date != '0000-00-00'){
                $post['start_date'] = $start_date;
            }

            if(!empty($end_date) && $end_date != '0000-00-00'){
                $post['end_date'] = $end_date;
            }

            if ($insert = Discounts::create($post))
            {
                $id = $insert->id;

                if($type == 'Clients') {
                    if (count($relations) > 0) {
                        foreach ($relations AS $relation) {
                            $post = array(
                                'discounts_id' => $id,
                                'clients_id' => $relation
                            );
                            DiscountsClients::create($post);
                        }
                    }
                }
                if($type == 'Products') {
                    if (count($relations) > 0) {
                        foreach ($relations AS $relation) {
                            $post = array(
                                'discounts_id' => $id,
                                'products_id' => $relation
                            );
                            DiscountsProducts::create($post);
                        }
                    }
                }
                if($type == 'Categories') {
                    if (count($relations) > 0) {
                        foreach ($relations AS $relation) {
                            $post = array(
                                'discounts_id' => $id,
                                'categories_id' => $relation
                            );
                            DiscountsCategories::create($post);
                        }
                    }
                }
                if($type == 'Brands') {
                    if (count($relations) > 0) {
                        foreach ($relations AS $relation) {
                            $post = array(
                                'discounts_id' => $id,
                                'brands_id' => $relation
                            );
                            DiscountsBrands::create($post);
                        }
                    }
                }

                self::sendEmail($id);

                if(LOG_GENERATE === true){
                    Log::create([
                        'id_user' => Helper::sessionSystemValue('id'),
                        'date' => Helper::getDate($hour = false),
                        'hour' => Helper::getHour(),
                        'ip' => Helper::getIP(),
                        'module' => $this->module,
                        'action' => 'INGRESO',
                        'identifier' => $id,
                        'detail' => 'Ingresó nuevo descuento "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                    ]);
                }

                session()->flash('error', 'success');
                return redirect()->route('discounts');
            } else {
                session()->flash('error', 'failure');
                return redirect()->route('discounts.enter');
            }
        }
    }

    public function edit($id){

    
        $discount = Discounts::findOrFail($id);

        $discount->start_date = ($discount->start_date == '0000-00-00' || $discount->start_date == '')? '' : Helper::dateFormatUser($discount->start_date, false);
        $discount->end_date = ($discount->end_date == '0000-00-00'  || $discount->end_date == '')? '' : Helper::dateFormatUser($discount->end_date, false);


        return view('admvisch.discounts.index')->with(['title'=>$this->title,'module'=>$this->module, 'discount'=>$discount, 'types'=>$this->types, 'image_description'=>$this->image_description]);
    }

    public function update(){


        $id = Helper::postValue('id');
        $author=(isset($_POST['author']) && !empty($_POST['author']))? Helper::postValue('author') : '';

        $discounts = Discounts::where('code', Helper::postValue('code'))->where('id', '<>', $id)->get()->count();
        if($discounts > 0){
            session()->flash('error', 'duplicate');
            return redirect()->route('discounts.edit',$id);
        } else {

            $type = Helper::postValue('type', 'Sale');
            $relations = (isset($_POST["relations"]) && !empty($_POST["relations"]))? $_POST["relations"] : [];

            if(($type == 'Clients' || $type == 'Categories' || $type == 'Brands' || $type == 'Products') && count($relations) == 0){
                session()->flash('error', 'selected');
            return redirect()->route('discounts.edit',$id);
            }

            $discount_percentage = Helper::postValue('discount_percentage', 0);
            $discount_percentage = (is_float($discount_percentage))? $discount_percentage : floatval($discount_percentage);
            $discount_percentage = ($discount_percentage > 100)? 100 : $discount_percentage;

            $discount = Helper::postValue('discount', 0);
            $discount = (is_int($discount))? $discount : intval($discount);

            if($discount == 0 && $discount_percentage == 0){
                session()->flash('error', 'discount');
            return redirect()->route('discounts.edit',$id);
            }

            $start_date = (isset($_POST['start_date']) && !empty($_POST['start_date']))? Helper::dateFormatSystem(Helper::postValue('start_date')) : '';
            $end_date = (isset($_POST['end_date']) && !empty($_POST['end_date']))? Helper::dateFormatSystem(Helper::postValue('end_date')) : '';

            $send = Helper::postValue('send', 0);
            $active = Helper::postValue('active', 0);

            $post = array(
                'title' => Helper::postValue('title'),
                'description' => Helper::postValue('description'),
                'code' => Helper::postValue('code'),
                'discount_percentage' => $discount_percentage,
                'discount' => $discount,
                'restrictions' => Helper::postValue('restrictions'),
                'type' => $type,
                'send' => $send,
                'active' => $active,
                'author' => $author
            );

            if(!empty($start_date) && $start_date != '0000-00-00'){
                $post['start_date'] = $start_date;
            }

            if(!empty($end_date) && $end_date != '0000-00-00'){
                $post['end_date'] = $end_date;
            }

            if ($update = Discounts::findOrFail($id)->update($post))
            {
                DB::delete('DELETE FROM discounts_clients WHERE discounts_id = :discounts_id', array(':discounts_id' => $id));
                DB::delete('DELETE FROM discounts_products WHERE discounts_id = :discounts_id', array(':discounts_id' => $id));
                DB::delete('DELETE FROM discounts_categories WHERE discounts_id = :discounts_id', array(':discounts_id' => $id));
                DB::delete('DELETE FROM discounts_brands WHERE discounts_id = :discounts_id', array(':discounts_id' => $id));

                if($type == 'Clients') {
                    if (count($relations) > 0) {
                        foreach ($relations AS $relation) {
                            $post = array(
                                'discounts_id' => $id,
                                'clients_id' => $relation
                            );
                            DiscountsClients::create($post);
                        }
                    }
                }
                if($type == 'Products') {
                    if (count($relations) > 0) {
                        foreach ($relations AS $relation) {
                            $post = array(
                                'discounts_id' => $id,
                                'products_id' => $relation
                            );
                            DiscountsProducts::create($post);
                        }
                    }
                }
                if($type == 'Categories') {
                    if (count($relations) > 0) {
                        foreach ($relations AS $relation) {
                            $post = array(
                                'discounts_id' => $id,
                                'categories_id' => $relation
                            );
                            DiscountsCategories::create($post);
                        }
                    }
                }
                if($type == 'Brands') {
                    if (count($relations) > 0) {
                        foreach ($relations AS $relation) {
                            $post = array(
                                'discounts_id' => $id,
                                'brands_id' => $relation
                            );
                            DiscountsBrands::create($post);
                        }
                    }
                }

                self::sendEmail($id);

                if(LOG_GENERATE === true){
                    Log::create([
                        'id_user' => Helper::sessionSystemValue('id'),
                        'date' => Helper::getDate($hour = false),
                        'hour' => Helper::getHour(),
                        'ip' => Helper::getIP(),
                        'module' => $this->module,
                        'action' => 'ACTUALIZACION',
                        'identifier' => $id,
                        'detail' => 'Actualizó descuento "' . Helper::postValue('title') . '" con ID N°' . $id . '.'
                    ]);
                }

                session()->flash('error', 'success');
                return redirect()->route('discounts');
            } else {
                session()->flash('error', 'failure');
                return redirect()->route('discounts.edit',$id);
            }
        }
    }

    public function delete($id){


        $discount = Discounts::findOrFail($id);

        DB::delete('DELETE FROM discounts_clients WHERE discounts_id = :discounts_id', array(':discounts_id' => $id));
        DB::delete('DELETE FROM discounts_products WHERE discounts_id = :discounts_id', array(':discounts_id' => $id));
        DB::delete('DELETE FROM discounts_categories WHERE discounts_id = :discounts_id', array(':discounts_id' => $id));
        DB::delete('DELETE FROM discounts_brands WHERE discounts_id = :discounts_id', array(':discounts_id' => $id));

        if($delete = Discounts::findOrFail($id)->delete()){

            if(LOG_GENERATE === true){
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó descuento "' . $discount->title . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('discounts');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('discounts');
        }
    }

    public function status(){

        $class_status = $text_status = '';
        $status = 0;

        if(isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['id']) && !empty($_POST['id']))
        {
            $id = Helper::postValue('id');
            $module_name = Helper::postValue('module_name');

            $faq = Discounts::findOrFail($id);

            $active = ($faq->active == 0)? 1 : 0;

            $post = array(
                'active' => $active,
                'author' => Auth::user()->name
            );

            if ($update = Discounts::findOrFail($id)->update($post))
            {
                $class_status = ($active == 1)? "badge-success" : "badge-default";
                $text_status = ($active == 1)? "Activo" : "Inactivo";
                $status = 1;
            }
        }

        echo json_encode(array("status" => $status, "class_status" => $class_status, "text_status" => $text_status));
    }

    public function sendEmail($id){

        $discount = Discounts::find($id);

        if($discount->send == 1 && $discount->active == 1)
        {
            $configuration = Configurations::where(['id' => 1])->orderBy('id', 'desc')->first();
            $metadata = Metadata::where(['id' => 1])->orderBy('id', 'desc')->first();
            $discountsClients = DiscountsClients::where(['discounts_id' => $id])->get();

            $idsClients = [];
            if($discountsClients->count() > 0){
                foreach($discountsClients AS $discountsClient){
                    $idsClients[] = $discountsClient->clients_id;
                }
            }

            $clients = Clients::where(['active' => 1]);
            if(count($idsClients) > 0){
                $clients = $clients->whereIn('id', $idsClients);
            }
            $clients = $clients->get();

            $WebMailVentas = $configuration->sale_email;
            $name_ecommerce = APP_COMPANY;
            $WebAppNameAdicional = 'Venta Online';
            $URL = BASE_URL_ROOT;
            $WebFecha = date('Y');
            $WebTitulo = $metadata->title;

            $emailFrom = '';
            $arrayEmails = explode(',', $WebMailVentas);

            if(is_array($arrayEmails)){
                foreach($arrayEmails AS $emailDestinatary){
                    $emailFrom = $emailDestinatary;
                    break;
                }
            } else {
                $emailFrom = $arrayEmails;
            }

            if($clients->count() > 0)
            {
                foreach($clients AS $client)
                {
                    $mail = new PHPMailer();

                    $mail->AddEmbeddedImage(ROOT . 'public' . DS . 'themes' . DS . DEFAULT_LAYOUT . DS . 'images' . DS . 'header.jpg', 'imgHeader', 'attachment', 'base64', 'image/jpeg');

                    $CSS_TABLE_MAIN = 'style="margin-left: auto; margin-right: auto; padding: 0; box-shadow: 0 0 10px rgba(0,0,0,.2); font-family: sans-serif; font-size: 14px; background: #FFF; border: 1px solid #ddd; width: 635px; color: #555555; line-height: 18px; border-spacing: 0; border-radius: 6px;"';
                    $CSS_H1_MAIN = 'style="margin: 10px 0; color: ' . MAIL_COLOR_TEXT . '; font-size: 26px;"';
                    $CSS_H1_MAIN_STRONG = 'style="color:#555"';
                    $CSS_HR = 'style="display: block; border: none; border-top: 2px solid #f2f2f2;"';
                    $CSS_TABLE_SECONDARY = 'style="width: 100%; border: 1px solid #ddd; border-bottom: 0; border-spacing: 0; font-size: 12px; line-height: 16px;"';
                    $CSS_FOOTER = 'style="display: block; padding: 10px; margin: 0; background: ' . MAIL_COLOR_TEXT . '; color: #FFF; text-align: center;font-size:12px;"';
                    $CSS_FOOTER_LINK = 'style="color: #FFF; text-decoration:none;"';
                    $CSS_TABLE_SECONDARY_BODY_TH = 'style="border-bottom: 1px solid #ddd; padding: 5px; background-color:' . MAIL_COLOR_BACKGROUND . '; text-align: left; border-right: 1px solid #ddd;"';
                    $CSS_TABLE_SECONDARY_BODY_TD = 'style="border-bottom: 1px solid #ddd; padding: 5px; background-color: #fff; text-align: left; border-right: 1px solid #ddd;"';

                    $subject_custom = ($discount->discount_percentage > 0)? $discount->discount_percentage . '%' : '$' . Helper::formatDecimals($discount->discount_percentage, 0);

                    $title_message = ($discount->discount_percentage > 0)? '-' . $discount->discount_percentage . '%' : '$' . Helper::formatDecimals($discount->discount_percentage, 0);
                    $title_message = $title_message . ' <strong ' . $CSS_H1_MAIN_STRONG . '>de descuento en tu próxima compra.</strong>';

                    $description_message = '<p>Felicitaciones, utiliza este cupón de descuento "<strong>' . $discount->code . '</strong>" que te otorgamos para utilizar en tu próxima compra. Obtendras <strong>' . $subject_custom . ' de descuento.</strong></p>
                    <a href="' . $URL . '" target="_blank" style="display: inline-block; padding: 7px 15px; background:#1d7bde; color: #FFF; font-weight: bold; border-radius: 4px; margin: 15px 0; text-decoration: none;">Ir a ' . $name_ecommerce . '</a>';

                    $restriction_message = '<small>Promoción válida solo para RUT ' . $client->rut . '<br>
                    Utiliza tu cupón entre el ' . Helper::dateFormatUser($discount->start_date, false) . ' hasta el ' . Helper::dateFormatUser($discount->end_date, false) . '.<br>
                    ' . $discount->restrictions . '
                    Válido un solo cupón por cliente en ' . $name_ecommerce . '.';

                    $mail->From = $emailFrom;
                    $mail->FromName = utf8_encode("=?UTF-8?B?" . base64_encode(APP_NAME) .  "?=");
                    $asunto = 'Felicitaciones! Tienes ' . $subject_custom . ' descuento en ' . APP_NAME . ' ' . $WebAppNameAdicional;
                    $mail->Subject = utf8_encode('=?UTF-8?B?' . base64_encode($asunto) . '?=');
                    $mail->AddAddress($client->email, $client->business_name);

                    $body = '
                    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http: //www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html xmlns="http: //www.w3.org/1999/xhtml">
                    
                       <head>
                          <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                          <title>Documento sin título</title>
                       </head>
                    
                       <body style="background: #f4f4f4; padding: 20px;">
                          <table width="635" border="0" valign="top" ' . $CSS_TABLE_MAIN . '>
                             <tbody>
                                <tr>
                                   <td style="padding: 0; margin: 0; border: 0; width: 635px;">
                                      <img src="cid:imgHeader" style="border-radius: 6px 6px 0 0; border-bottom:1px solid #f2f2f2">
                                   </td>
                                </tr>
                                <tr><!-- TITULAR -->
                                   <td style="display: block; padding: 5px 15px; text-align: center;">
                                      <h1 ' . $CSS_H1_MAIN .'>' . $title_message . '</h1>
                                   </td>
                                </tr>
                    
                                <tr><!-- SEPARADOR -->
                                   <td style="display: block; padding: 0 15px;"><hr ' . $CSS_HR . '></td>
                                </tr>
                    
                                <tr><!-- CONTENIDO TITULAR -->
                                   <td style="display: block; padding: 5px 15px;text-align: center;">
                                      ' . $description_message . '
                                   </td>
                                </tr>
                    
                                <tr><!-- SEPARADOR -->
                                   <td style="display: block; padding: 15px;"><hr ' . $CSS_HR . '></td>
                                </tr>
                                
                                <tr><!-- SEPARADOR -->
                                   <td style="display: block; padding: 15px; text-align: center; font-size:13px; ">
                                      ' . $restriction_message . '
                                   </td>
                                </tr>
                    
                                <tr><!-- AVISO RESPUESTA AUTOMATICA -->
                                   <td style="display: block; padding: 10px; background: #f6f6f6; color:#999; margin-top: 20px; text-align: center; font-size:11px;">
                                      Este correo se ha generado de forma automatica, favor no responder.
                                   </td>
                                </tr>
                    
                                <tr ><!-- FOOTER -->
                                   <td ' . $CSS_FOOTER . '>
                                      <a href="' . $URL . '" target="_self" ' . $CSS_FOOTER_LINK . '>' . $WebFecha . ' - ' . $WebTitulo . '</a>
                                   </td>
                                </tr>
                             </tbody>
                          </table>
                    
                       </body>
                    </html>
                    ';

                    $mail->Body = utf8_decode($body);
                    $mail->IsHTML(true);

                    if ($mail->Send()) {
                        $mail->ClearAddresses();
                        $mail->ClearAllRecipients();
                    } else {
                        $mail->ClearAddresses();
                        $mail->ClearAllRecipients();
                    }
                }
            }
        }
    }

    public function getClients(){

        $id = Helper::postValue('id', 0);

        $relations_selected = '';
        $arrayClientsSelected = array();

        if(!empty($id) && $id > 0){
            $clientsRelations = DiscountsClients::where(['discounts_id' => $id])->get();

            if($clientsRelations->count() > 0) {
                foreach ($clientsRelations AS $relation) {
                    $arrayClientsSelected[] = $relation->clients_id;
                }
            }
        }

        $clients = Clients::where(['active' => 1])->orderBy('business_name')->get();
        if($clients->count() > 0) {
            foreach ($clients AS $client) {
                $selected = (in_array($client->id, $arrayClientsSelected))? 'selected="selected"' : '';
                $relations_selected .= '<option value="' . $client->id . '" ' . $selected . '>' . $client->business_name . ' - ' . $client->rut . '</option>';
            }
        }

        echo json_encode(array("relations_selected" => $relations_selected));
    }

    public function getCategories(){

        $id = Helper::postValue('id', 0);

        $relations_selected = '';
        $arrayCategoriesSelected = array();

        if(!empty($id) && $id > 0){
            $categoriesRelations = DiscountsCategories::where(['discounts_id' => $id])->get();

            if($categoriesRelations->count() > 0) {
                foreach ($categoriesRelations AS $relation) {
                    $arrayCategoriesSelected[] = $relation->categories_id;
                }
            }
        }

        $categories = Categories::where(['active' => 1, 'parent_id' => 0])->orderBy('title')->get();
        if($categories->count() > 0) {
            foreach ($categories AS $category) {
                $selected = (in_array($category->id, $arrayCategoriesSelected))? 'selected="selected"' : '';
                $relations_selected .= '<option value="' . $category->id . '" ' . $selected . '>' . $category->title . '</option>';
            }
        }

        echo json_encode(array("relations_selected" => $relations_selected));
    }

    public function getBrands(){

        $id = Helper::postValue('id', 0);

        $relations_selected = '';
        $arrayBrandsSelected = array();

        if(!empty($id) && $id > 0){
            $brandsRelations = DiscountsBrands::where(['discounts_id' => $id])->get();

            if($brandsRelations->count() > 0) {
                foreach ($brandsRelations AS $relation) {
                    $arrayBrandsSelected[] = $relation->brands_id;
                }
            }
        }

        $brands = Brands::where(['active' => 1])->orderBy('title')->get();
        if($brands->count() > 0) {
            foreach ($brands AS $brand) {
                $selected = (in_array($brand->id, $arrayBrandsSelected))? 'selected="selected"' : '';
                $relations_selected .= '<option value="' . $brand->id . '" ' . $selected . '>' . $brand->title . '</option>';
            }
        }

        echo json_encode(array("relations_selected" => $relations_selected));
    }

    public function getProducts(){

        $id = Helper::postValue('id', 0);

        $relations_selected = '';
        $arrayProductsSelected = array();

        if(!empty($id) && $id > 0){
            $productsRelations = DiscountsProducts::where(['discounts_id' => $id])->get();

            if($productsRelations->count() > 0) {
                foreach ($productsRelations AS $relation) {
                    $arrayProductsSelected[] = $relation->products_id;
                }
            }
        }

        $products = Products::where(['active' => 1])->orderBy('sku')->get();
        if($products->count() > 0) {
            foreach ($products AS $product) {
                $selected = (in_array($product->id, $arrayProductsSelected))? 'selected="selected"' : '';
                $relations_selected .= '<option value="' . $product->id . '" ' . $selected . '>' . $product->sku . ' - ' . $product->title . '</option>';
            }
        }

        echo json_encode(array("relations_selected" => $relations_selected));
    }
}
