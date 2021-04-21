<?php

namespace App\Http\Controllers;

use App\Exports\ClientsExport;
use App\Models\Clients;
use App\Models\Configurations;
use App\Models\Locations;
use App\Models\Log;
use App\Models\Metadata;
use App\Models\Provinces;
use App\Models\Regions;
use Application\Hash;
use Application\Helper;
use Application\HelperExcel;
use Illuminate\Http\Request;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use Libraries\Upload\Upload;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel;
use PHPExcel_Reader_Excel2007;

class ClientsController extends Controller
{

    private $title = 'Clientes';
    private $parent_title = 'Gestión de Clientes';
    private $module = 'clients';


    public function index()
    {
        return view('admvisch.clients.index')->with(['title' => $this->title, 'parent_title' => $this->parent_title, 'module' => $this->module]);
    }

    public function pagRegistro()
    {
        $PaginaTitulo = "Registro";
        $regions = Regions::orderBy('position')->get();
        return view('pages.registro', compact('PaginaTitulo', 'regions'));
    }

    public function resetPassword()
    {
        $PaginaTitulo = "Recuperar Clave";
        return view('pages.resetPassword', compact('PaginaTitulo'));
    }

    public function documents()
    {

        $table_documents_body = array();

        $clients = Clients::orderBy('id', 'desc')->get();

        if (count($clients) > 0) {
            foreach ($clients as $client) {

                $status = $actions = '';

                $class_status = ($client->active == 1) ? "success" : "default";
                $text_status = ($client->active == 1) ? "Activo" : "Inactivo";


                $status = '<a style="cursor: pointer;" class="change-status" id="' . $client->id . '"><span class="badge badge-' . $class_status . '">' . $text_status . '</span></a>';


                $actions .= '<a type="button" class="btn btn-sm btn-gold" data-toggle="tooltip" data-placement="top" title="" data-original-title="Editar" href="' . URL_FRIENDLY_BASE . $this->module . '/edit/' . $client->id . '"><i class="fa fa fa-pencil-square-o"></i></a> ';


                $actions .= '<a type="button" class="btn btn-sm btn-danger delete-register" data-toggle="tooltip" data-placement="top" title="" data-original-title="Eliminar" id="' .  $client->id . '"><i class="fa fa-trash-o"></i></a>';


                $table_documents_body[] = array(
                    '#' . $client->id,
                    $client->rut,
                    $client->business_name,
                    $status,
                    Helper::dateFormatUser($client->updated_date),
                    $client->author,
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
        $regions = Regions::orderBy('position')->get();
        return view('admvisch.clients.enter')->with(['title' => $this->title, 'module' => $this->module, 'regions' => $regions, 'parent_title' => $this->parent_title]);
    }

    public function insert()
    {

        $clients = Clients::where(['rut' => Helper::postValue('rut'), 'email' => Helper::postValue('email')])->get()->count();

        if ($clients > 0) {
            session()->flash('error', 'failure');
            return redirect()->route('clients.enter');
        } else {

            $image = '';


            $generate_password = Helper::postValue('generate_password', 0);

            $region = Regions::where(['code' => Helper::postValue('document_regions_id')])->first();
            $province = Provinces::where(['code' => Helper::postValue('document_provinces_id')])->first();
            $location = Locations::where(['code' => Helper::postValue('document_locations_id')])->first();

            $author = (isset($_POST['author']) && !empty($_POST['author'])) ? Helper::postValue('author') : '';
            $post = array(
                'type' => Helper::postValue('type'),
                'rut' => Helper::postValue('rut'),
                'business_name' => Helper::postValue('business_name'),
                'commercial_business' => Helper::postValue('commercial_business'),
                'email' => Helper::postValue('email'),
                'phone' => Helper::postValue('phone'),
                'address' => Helper::postValue('document_address'),
                'address_number' => Helper::postValue('document_address_number'),
                'office_number' => Helper::postValue('document_office_number'),
                'image' => $image,
                'active' => Helper::postValue('active', 1),
                'author' => $author
            );

            if ($region) {
                $post['regions_id'] = $region->id;
            }
            if ($province) {
                $post['provinces_id'] = $province->id;
            }
            if ($location) {
                $post['locations_id'] = $location->id;
            }

            if ($generate_password == 1) {
                $password = Helper::randomString(8, true, true, true, true);
                $post['password'] = bcrypt($password);
            } else {
                $password = Helper::postValue('password');
                $post['password'] = bcrypt($password);
            }

            if ($insert = Clients::create($post)) {
                $id = $insert->id;

                /********************************************************************************************************************************************************/

                $subject = 'Activación de cliente - ' . APP_NAME;
                $title = 'Activación';
                $message = '';

                self::sendEmail($id, $password, $subject, $title, $message);

                /********************************************************************************************************************************************************/

                if (LOG_GENERATE === true) {
                    Log::create([
                        'id_user' => Helper::sessionSystemValue('id'),
                        'date' => Helper::getDate($hour = false),
                        'hour' => Helper::getHour(),
                        'ip' => Helper::getIP(),
                        'module' => $this->module,
                        'action' => 'INGRESO',
                        'identifier' => $id,
                        'detail' => 'Ingresó nuevo cliente "' . Helper::postValue('rut') . '" con ID N°' . $id . '.'
                    ]);
                }

                session()->flash('error', 'success');
                return redirect()->route('clients');
            } else {

                session()->flash('error', 'failure');
                return redirect()->route('contacts.enter');
            }
        }
    }

    public function insertClientes()
    {

        $clients = Clients::where(['rut' => Helper::postValue('rut'), 'email' => Helper::postValue('email')])->get()->count();

        if ($clients > 0) {
            session()->flash('error', 'failure');
            return redirect()->route('registro');
        } else {

            $image = '';

            $region = Regions::where(['code' => Helper::postValue('document_regions_id')])->first();
            $province = Provinces::where(['code' => Helper::postValue('document_provinces_id')])->first();
            $location = Locations::where(['code' => Helper::postValue('document_locations_id')])->first();

            $post = array(
                'type' => '',
                'rut' => Helper::postValue('rut'),
                'business_name' => Helper::postValue('business_name'),
                'commercial_business' => '',
                'email' => Helper::postValue('email'),
                'phone' => Helper::postValue('phone'),
                'address' => Helper::postValue('document_address'),
                'image' => $image,
                'active' => 1,
                'author' => ''
            );

            if ($region) {
                $post['regions_id'] = $region->id;
            }
            if ($province) {
                $post['provinces_id'] = $province->id;
            }
            if ($location) {
                $post['locations_id'] = $location->id;
            }

            $password = Helper::postValue('password');
            $post['password'] = bcrypt($password);

            if ($insert = Clients::create($post)) {
                $id = $insert->id;

                /********************************************************************************************************************************************************/

                $subject = 'Activación de cliente - ' . APP_NAME;
                $title = 'Activación';
                $message = '';

                self::sendEmail($id, $password, $subject, $title, $message);

                /********************************************************************************************************************************************************/

                if (LOG_GENERATE === true) {
                    Log::create([
                        'id_user' => Helper::sessionSystemValue('id'),
                        'date' => Helper::getDate($hour = false),
                        'hour' => Helper::getHour(),
                        'ip' => Helper::getIP(),
                        'module' => $this->module,
                        'action' => 'INGRESO',
                        'identifier' => $id,
                        'detail' => 'Ingresó nuevo cliente "' . Helper::postValue('rut') . '" con ID N°' . $id . '.'
                    ]);
                }

                session()->flash('error', 'success');
                return redirect()->route('registro');
            } else {

                session()->flash('error', 'failure');
                return redirect()->route('registro');
            }
        }
    }

    public function edit($id)
    {

        $client = Clients::findOrFail($id);

        $regions = Regions::orderBy('position')->get();
        $provinces = $locations = '';
        if (!empty($client->regions_id)) {
            $provinces = Provinces::where(['parent_code' => $client->region->code])->get();
        }
        if (!empty($client->provinces_id)) {
            $locations = Locations::where(['parent_code' => $client->province->code])->get();
        }

        return view('admvisch.clients.edit')->with(['title' => $this->title, 'parent_title' => $this->parent_title, 'module' => $this->module, 'client' => $client, 'locations' => $locations, 'provinces' => $provinces, 'regions' => $regions]);
    }

    public function update()
    {

        $id = Helper::postValue('id');

        $clients = Clients::where(['rut' => Helper::postValue('rut'), 'email' => Helper::postValue('email')])->where('id', '<>', $id)->get()->count();
        $author = (isset($_POST['author']) && !empty($_POST['author'])) ? Helper::postValue('author') : '';

        if ($clients > 0) {
            session()->flash('error', 'duplicate');
            return redirect()->route('clients.edit', $id);
        } else {


            $image = '';


            $generate_password = Helper::postValue('generate_password', 0);
            $modificate_password = Helper::postValue('modificate_password', 0);

            $region = Regions::where(['code' => Helper::postValue('document_regions_id')])->first();
            $province = Provinces::where(['code' => Helper::postValue('document_provinces_id')])->first();
            $location = Locations::where(['code' => Helper::postValue('document_locations_id')])->first();

            $post = array(
                'type' => Helper::postValue('type'),
                'rut' => Helper::postValue('rut'),
                'business_name' => Helper::postValue('business_name'),
                'commercial_business' => Helper::postValue('commercial_business'),
                'address' => Helper::postValue('document_address'),
                'address_number' => Helper::postValue('document_address_number'),
                'office_number' => Helper::postValue('document_office_number'),
                'email' => Helper::postValue('email'),
                'phone' => Helper::postValue('phone'),
                'active' => Helper::postValue('active', 0),
                'author' => $author
            );

            if ($region) {
                $post['regions_id'] = $region->id;
            }
            if ($province) {
                $post['provinces_id'] = $province->id;
            }
            if ($location) {
                $post['locations_id'] = $location->id;
            }

            if ($modificate_password != 0) {
                if ($generate_password == 1) {
                    $password = Helper::randomString(8, true, true, true, true);
                    $post['password'] = bcrypt($password);
                } else {
                    $password = Helper::postValue('password');
                    $post['password'] = bcrypt($password);
                }
            }

            if (!empty($image)) {
                $post['image'] = $image;
            }

            if ($update = Clients::findOrFail($id)->update($post)) {
                if ($modificate_password != 0) {
                    /********************************************************************************************************************************************************/

                    $subject = 'Actualización de cliente - ' . APP_NAME;
                    $title = 'Actualización';
                    $message = '';

                    self::sendEmail($id, $password, $subject, $title, $message);

                    /********************************************************************************************************************************************************/
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
                        'detail' => 'Actualizó cliente "' . Helper::postValue('rut') . '" con ID N°' . $id . '.'
                    ]);
                }

                session()->flash('error', 'success');
                return redirect()->route('clients');
            } else {
                session()->flash('error', 'failure');
                return redirect()->route('clients.edit', $id);
            }
        }
    }

    public function delete($id)
    {

        $client = Clients::findOrFail($id);

        if ($delete = Clients::findOrFail($id)->delete()) {

            if (LOG_GENERATE === true) {
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó cliente "' . $client->rut . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('clients');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('clients');
        }
    }

    public function status()
    {

        $class_status = $text_status = '';
        $status = 0;

        if (isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['id']) && !empty($_POST['id'])) {
            $id = Helper::postValue('id');
            $module_name = Helper::postValue('module_name');

            $client = Clients::findOrFail($id);

            $active = ($client->active == 0) ? 1 : 0;

            $post = array(
                'active' => $active,
                'author' =>  Auth::user()->name
            );

            if ($update = Clients::findOrFail($id)->update($post)) {
                $class_status = ($active == 1) ? "badge-success" : "badge-default";
                $text_status = ($active == 1) ? "Activo" : "Inactivo";
                $status = 1;
            }
        }

        echo json_encode(array("status" => $status, "class_status" => $class_status, "text_status" => $text_status));
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

    public function sendEmail($id, $_password, $_subject, $_title, $_message)
    {
        $client = Clients::findOrFail($id);
        $configuration = Configurations::where(['id' => 1])->orderBy('id', 'desc')->first();
        $metadata = Metadata::where(['id' => 1])->orderBy('id', 'desc')->first();

        $WebMailContacto = $configuration->contact_email;
        $emailFrom = '';
        $arrayEmails = explode(',', $WebMailContacto);

        if (is_array($arrayEmails)) {
            foreach ($arrayEmails as $emailDestinatary) {
                $emailFrom = $emailDestinatary;
                break;
            }
        } else {
            $emailFrom = $arrayEmails;
        }

        $URL = BASE_URL_ROOT;
        $WebFecha = date('Y');
        $WebTitulo = $metadata->title;

        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

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

        $mail->From = $emailFrom;
        $mail->FromName = APP_NAME;
        $asunto = $_subject;
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
                          <h1 ' . $CSS_H1_MAIN . '>' . $_title . ' <strong ' . $CSS_H1_MAIN_STRONG . '>Cliente</strong></h1>
                       </td>
                    </tr>

                    <tr><!-- SEPARADOR -->
                       <td style="display: block; padding: 0 15px;"><hr ' . $CSS_HR . '></td>
                    </tr>

                    <tr><!-- CONTENIDO TITULAR -->
                       <td style="display: block; padding: 5px 15px;">
                          <p style="font-size:12px;">Estimado Sr(a) ' . $client->business_name . '  Esto es una copia de su solicitud de registro a través de nuestro sitio web.</p>
                          <p style="font-size:12px;">Desde ahora podrás acceder a:</p>
                          <ul style="font-size:12px;">
                             <li>Descuentos y Ofertas permanentes</li>
                             <li>Diferentes direcciones de envío</li>
                             <li>Diferentes formas de pago</li>
                             <li>Entrega a domicilio o si prefieres retirar en nuestras sucursales</li>
                             <li>Hacer seguimiento de tus pedidos</li>
                          </ul>
                          <p style="font-size:12px;">Para iniciar sesión en nuestra tienda, haz click en el botón "Iniciar Sesión", ubicado en la parte superior derecha de la página e introduce tu usuario y contraseña:</p>
                       </td>
                    </tr>

                    <tr><!-- CONTENIDO -->
                       <td style="display: block; padding: 5px 15px;">
                          <table width="100%" border="0" ' . $CSS_TABLE_SECONDARY . '>
                            <tbody>
                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                   <th width="30%" ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Usuario</th>
                                   <td width="70%" ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $client->email . '</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                   <th ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Contraseña</th>
                                   <td ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $_password . '</td>
                                </tr>
                             </tbody>
                          </table>
                       </td>
                    </tr>

                    <tr><!-- SEPARADOR -->
                       <td style="display: block; padding: 15px;"><hr ' . $CSS_HR . '></td>
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
            return "error al enviar";
        }
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
            $clients = Clients::orderBy('id')->get();

            if (count($clients) > 0) {
                foreach ($clients as $client) {
                    $data = array();

                    $active = ($client->active == 1) ? "SI" : "NO";

                    $data[] = $client->type;
                    $data[] = $client->rut;
                    $data[] = $client->business_name;
                    $data[] = $client->commercial_business;
                    $data[] = $client->email;
                    $data[] = $client->phone;
                    $data[] = $client->address;
                    $data[] = $client->address_number;
                    $data[] = $client->office_number;
                    $data[] = (!empty($client->regions_id)) ? $client->region->description : '';
                    $data[] = (!empty($client->provinces_id)) ? $client->province->description : '';
                    $data[] = (!empty($client->locations_id)) ? $client->location->description : '';
                    $data[] = "NO";
                    $data[] = "NO";
                    $data[] = "";
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

        HelperExcel::applyExcelOutput($objPHPExcel, "clientes-" . date('YmdHms'));
    }

    public function import()
    {

        $title = 'Importar Clientes';
        return view('admvisch.clients.import')->with(['title' => $title, 'parent_title' => $this->parent_title, 'module' => $this->module]);
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




    public function loadClientsByExcel()
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

                break;
            }

            $result = array_diff($header, $headerXLS);
            if (count($result) == 0) {
                $i = 2;

                /**
                 * Segundo reccorremos el excel para comprobar ingreso y/o actualización del producto
                 **/

                while ($xls->getActiveSheet()->getCell("A" . $i)->getValue() != '') {
                    $type = trim($xls->getActiveSheet()->getCell("A" . $i)->getValue());
                    $rut = trim($xls->getActiveSheet()->getCell("B" . $i)->getValue());
                    $business_name = trim($xls->getActiveSheet()->getCell("C" . $i)->getValue());
                    $commercial_business = trim($xls->getActiveSheet()->getCell("D" . $i)->getValue());
                    $email = trim($xls->getActiveSheet()->getCell("E" . $i)->getValue());
                    $phone = trim($xls->getActiveSheet()->getCell("F" . $i)->getValue());
                    $address = trim($xls->getActiveSheet()->getCell("G" . $i)->getValue());
                    $address_number = trim($xls->getActiveSheet()->getCell("H" . $i)->getValue());
                    $office_number = trim($xls->getActiveSheet()->getCell("I" . $i)->getValue());
                    $region_title = trim($xls->getActiveSheet()->getCell("J" . $i)->getValue());
                    $province_title = trim($xls->getActiveSheet()->getCell("K" . $i)->getValue());
                    $location_title = trim($xls->getActiveSheet()->getCell("L" . $i)->getValue());
                    $generate_password = trim($xls->getActiveSheet()->getCell("M" . $i)->getValue());
                    $modificate_password = trim($xls->getActiveSheet()->getCell("N" . $i)->getValue());
                    $password = trim($xls->getActiveSheet()->getCell("O" . $i)->getValue());
                    $active = trim($xls->getActiveSheet()->getCell("P" . $i)->getValue());

                    $generate_password = ($generate_password == 'SI') ? 1 : 0;
                    $modificate_password = ($modificate_password == 'SI') ? 1 : 0;
                    $active = ($active == 'SI') ? 1 : 0;

                    $response = array(
                        'code' => $rut,
                        'description' => $business_name,
                        'message' => '',
                        'line' => $i
                    );

                    /**
                     ** Comprobamos si el producto existe a partir de su sku = código grupo
                     **/

                    $regions = Regions::where(['description' => $region_title])->get();
                    $provinces = Provinces::where(['description' => $province_title])->get();
                    $locations = Locations::where(['description' => $location_title])->get();

                    $clients = Clients::where(['rut' => $rut, 'email' => $email])->get();
                    if ($clients->count() > 0) {
                        $client = $clients->first();
                        $id = $client->id;

                        if ($regions->count() == 0 || $provinces->count() == 0 || $locations->count() == 0) {
                            $response["message"] = 'Error de geolocalización.';
                            $response_data[] = $response;
                        } else {

                            $region = $regions->first();
                            $province = $provinces->first();
                            $location = $locations->first();

                            $post = array(
                                'type' => $type,
                                'rut' => $rut,
                                'business_name' => $business_name,
                                'commercial_business' => $commercial_business,
                                'address' => $address,
                                'address_number' => $address_number,
                                'office_number' => $office_number,
                                'regions_id' => $region->id,
                                'provinces_id' => $province->id,
                                'locations_id' => $location->id,
                                'email' => $email,
                                'phone' => $phone,
                                'active' => $active,
                                'author' => Auth::user()->name
                            );

                            if ($modificate_password != 0) {
                                if ($generate_password == 1) {
                                    $password = Helper::randomString(8, true, true, true, true);
                                    $post['password'] = bcrypt($password);
                                } else {
                                    $password = Helper::postValue('password');
                                    $post['password'] = bcrypt($password);
                                }
                            }

                            if ($update = Clients::findOrFail($id)->update($post)) {
                                $number_update++;

                                $_subject = 'Actualización de cliente - ' . APP_NAME;
                                $_title = 'Actualización';
                                $_message = '';

                                self::sendEmail($id, $password, $_subject, $_title, $_message);
                            } else {
                                $response["message"] = 'Error al actualizar registro.';
                                $response_data[] = $response;
                            }
                        }
                    } else {

                        if ($regions->count() == 0 || $provinces->count() == 0 || $locations->count() == 0) {
                            $response["message"] = 'Error de geolocalización.';
                            $response_data[] = $response;
                        } else {

                            $region = $regions->first();
                            $province = $provinces->first();
                            $location = $locations->first();

                            $post = array(
                                'type' => $type,
                                'rut' => $rut,
                                'business_name' => $business_name,
                                'commercial_business' => $commercial_business,
                                'address' => $address,
                                'address_number' => $address_number,
                                'office_number' => $office_number,
                                'regions_id' => $region->id,
                                'provinces_id' => $province->id,
                                'locations_id' => $location->id,
                                'email' => $email,
                                'phone' => $phone,
                                'active' => $active,
                                'author' => Auth::user()->name
                            );

                            if ($modificate_password != 0) {
                                if ($generate_password == 1) {
                                    $password = Helper::randomString(8, true, true, true, true);
                                    $post['password'] = bcrypt($password);
                                } else {
                                    $password = Helper::postValue('password');
                                    $post['password'] = bcrypt($password);
                                }
                            }

                            if ($insert = Clients::create($post)) {
                                $number_insert++;
                                $id = $insert->id;

                                $_subject = 'Activación de cliente - ' . APP_NAME;
                                $_title = 'Activación';
                                $_message = '';

                                self::sendEmail($id, $password, $_subject, $_title, $_message);
                            } else {
                                $response["message"] = 'Error al ingresar registro.';
                                $response_data[] = $response;
                            }
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

    public function getHeaderXLS()
    {

        $header = array();

        $header[] = "Tipo";
        $header[] = "Rut";
        $header[] = "Nombre / Razón Social";
        $header[] = "Giro";
        $header[] = "Email";
        $header[] = "Teléfono";
        $header[] = "Dirección";
        $header[] = "Numeración Calle";
        $header[] = "Número Departamento / Oficina";
        $header[] = "Región";
        $header[] = "Provincia";
        $header[] = "Comuna";
        $header[] = "Clave Automática";
        $header[] = "Modificar Clave";
        $header[] = "Clave";
        $header[] = "Activo";

        return $header;
    }
}
