<?php

namespace App\Http\Controllers;

use App\Models\Configurations;
use App\Models\Log;
use App\Models\Metadata;
use App\Models\User;
use Application\Hash;
use Application\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPMailer\PHPMailer\PHPMailer;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
   
    private $title = 'Gestión de Usuarios';
    private $parent_title = 'Configuración Global';
    private $module = 'users';

    public function index(){

        $users = User::orderBy('id', 'desc')->get();

        return view('admvisch.users.index')->with(['title'=>$this->title, 'parent_title'=>$this->parent_title, 'module'=>$this->module, 'users'=>$users]);
    }

    public function enter(){

      
        $roles = Role::orderBy('id')->get();
        return view('admvisch.users.enter')->with(['title'=>$this->title, 'parent_title'=>$this->parent_title, 'module'=>$this->module, 'roles'=>$roles]);
    }

    public function insert(Request $request){


       
        $users = User::where(['rut' => Helper::postValue('rut')])->get()->count();
        $user = User::where(['rut' => Helper::postValue('rut')]);

        
        if($users > 0){
            session()->flash('error', 'duplicate');
            return redirect()->route('users.enter');
        } else {

           
            $post = array(
                'rut' => Helper::postValue('rut'),
                'name' => Helper::postValue('name'),
                'email' => Helper::postValue('email'),
                'password' => bcrypt(Helper::postValue('password')),
                'active' => Helper::postValue('active', 0)
               
            );

            if($insert = User::create($post)->assignRole($request->roles_id))
            {
                $id = $insert->id;

                /********************************************************************************************************************************************************/

                $password = Helper::postValue('password');

                $subject = 'Habilitación de usuario - ' . APP_NAME . ' (PANEL DE ADMINISTRACIÓN)';
                $title = 'Habilitación';
                $message = 'Esto es una copia de su solicitud de habilitación de usuario.';

                self::sendEmail($id, $password, $subject, $title, $message);

                /********************************************************************************************************************************************************/

                if(LOG_GENERATE === true){
                    Log::create([
                        'id_user' => Helper::sessionSystemValue('id'),
                        'date' => Helper::getDate($hour = false),
                        'hour' => Helper::getHour(),
                        'ip' => Helper::getIP(),
                        'module' => $this->module,
                        'action' => 'INGRESO',
                        'identifier' => $id,
                        'detail' => 'Ingresó nuevo usuario "' . Helper::postValue('name') . '" con ID N°' . $id . '.'
                    ]);
                }

                session()->flash('error', 'success');
            return redirect()->route('users');
            } else {
                session()->flash('error', 'failure');
                return redirect()->route('users');
            }
        }
    }

    public function edit($id){

    
        $roles = Role::orderBy('id')->get();

        $user = User::findOrFail($id);

        return view('admvisch.users.edit')->with(['title'=>$this->title, 'parent_title'=>$this->parent_title, 'module'=>$this->module, 'roles'=>$roles, 'user'=>$user]);

    }

    public function update(Request $request){


       

        $id = Helper::postValue('id');
        $rol = Role::all();
        $user = User::findOrFail($id);

        $users = User::where(['rut' => Helper::postValue('rut'), 'email' => Helper::postValue('email')])->where('id', '<>', $id)->get()->count();

        if($users > 0){
            session()->flash('error', 'duplicate');
            return redirect()->route('users.edit',$id);
        } else {
    

            foreach($rol as $r){

                if($user->hasRole($r->name)){
                    $user->removeRole($r->name);
                }
            }

            $user->assignRole($request->roles_id);
            $post = array(
                'rut' => Helper::postValue('rut'),
                'name' => Helper::postValue('name'),
                'email' => Helper::postValue('email'),
                'active' => Helper::postValue('active', 0),
              
            );

            if(isset($_POST['change_password']) && !empty($_POST['change_password'])){
                $post['password'] =  bcrypt(Helper::postValue('password'));
            }

            if ($update = User::findOrFail($id)->update($post))
            {
                /********************************************************************************************************************************************************/

                if(isset($_POST['change_password']) && !empty($_POST['change_password']))
                {
                    $password = Helper::postValue('password');

                    $subject = 'Actualización de usuario - ' . APP_NAME . ' (PANEL DE ADMINISTRACIÓN)';
                    $title = 'Actualización';
                    $message = 'Esto es una copia de su solicitud de actualización de usuario.';

                    self::sendEmail($id, $password, $subject, $title, $message);
                }

                /********************************************************************************************************************************************************/

                if(LOG_GENERATE === true){
                    Log::create([
                        'id_user' => Helper::sessionSystemValue('id'),
                        'date' => Helper::getDate($hour = false),
                        'hour' => Helper::getHour(),
                        'ip' => Helper::getIP(),
                        'module' => $this->module,
                        'action' => 'ACTUALIZACION',
                        'identifier' => $id,
                        'detail' => 'Actualizó usuario "' . Helper::postValue('name') . '" con ID N°' . $id . '.'
                    ]);
                }

                session()->flash('error', 'success');
                return redirect()->route('users');
            } else {
                session()->flash('error', 'failure');
                return redirect()->route('users.edit',$id);
            }
        }
    }

    public function delete($id){

        $user = User::findOrFail($id);

        if($delete = User::findOrFail($id)->delete()){

            if(LOG_GENERATE === true){
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó usuario "' . $user->name . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('users');
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('users');
        }
    }

    public function status(){

        $class_status = $text_status = '';
        $status = 0;

        if(isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['id']) && !empty($_POST['id']))
        {
            $id = Helper::postValue('id');
            $module_name = Helper::postValue('module_name');

            $user = User::findOrFail($id);

            $active = ($user->active == 0)? 1 : 0;

            $post = array(
                'active' => $active,
                'author' => Auth::user()->name
            );

            if ($update = User::findOrFail($id)->update($post))
            {
                $class_status = ($active == 1)? "badge-success" : "badge-default";
                $text_status = ($active == 1)? "Activo" : "Inactivo";
                $status = 1;
            }
        }

        echo json_encode(array("status" => $status, "class_status" => $class_status, "text_status" => $text_status));
    }

    public function sendEmail($id, $_password, $_subject, $_title, $_message)
    {
        $user = User::findOrFail($id);
        $configuration = Configurations::where(['id' => 1])->orderBy('id', 'desc')->first();
        $metadata = Metadata::where(['id' => 1])->orderBy('id', 'desc')->first();

        $WebMailContacto = $configuration->contact_email;
        $emailFrom = '';
        $arrayEmails = explode(',', $WebMailContacto);

        if(is_array($arrayEmails)){
            foreach($arrayEmails AS $emailDestinatary){
                $emailFrom = $emailDestinatary;
                break;
            }
        } else {
            $emailFrom = $arrayEmails;
        }

        $URL = BASE_URL;
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
        $CSS_FOOTER_LINK = 'style="color: #000; text-decoration:none;"';
        $CSS_TABLE_SECONDARY_BODY_TH = 'style="border-bottom: 1px solid #ddd; padding: 5px; background-color:' . MAIL_COLOR_BACKGROUND . '; text-align: left; border-right: 1px solid #ddd;"';
        $CSS_TABLE_SECONDARY_BODY_TD = 'style="border-bottom: 1px solid #ddd; padding: 5px; background-color: #fff; text-align: left; border-right: 1px solid #ddd;"';

        $mail->From = $emailFrom;
        $mail->FromName = APP_NAME;
        $asunto = $_subject;
        $mail->Subject = utf8_encode('=?UTF-8?B?' . base64_encode($asunto) . '?=');
        $mail->AddAddress($user->email, $user->name);

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
                          <h1 ' . $CSS_H1_MAIN .'>' . $_title . ' <strong ' . $CSS_H1_MAIN_STRONG . '>Usuario</strong></h1>
                       </td>
                    </tr>
        
                    <tr><!-- SEPARADOR -->
                       <td style="display: block; padding: 0 15px;"><hr ' . $CSS_HR . '></td>
                    </tr>
        
                    <tr><!-- CONTENIDO TITULAR -->
                       <td style="display: block; padding: 5px 15px;">
                          <p style="font-size:12px;">Estimado Sr(a) ' . $user->name . ', ' . $_message . '</p>
                          <p style="font-size:12px;">Para iniciar sesión en nuestro panel de administración, introduce tu usuario y contraseña <a href="' . $URL . '" target="_self" ' . $CSS_FOOTER_LINK . '>aquí</a>:</p>
                       </td>
                    </tr>
        
                    <tr><!-- CONTENIDO -->
                       <td style="display: block; padding: 5px 15px;">
                          <table width="100%" border="0" ' . $CSS_TABLE_SECONDARY . '>
                            <tbody>
                                <tr style="border-bottom: 1px solid #ddd; vertical-align: top;">
                                   <th width="30%" ' . $CSS_TABLE_SECONDARY_BODY_TH . '>Usuario</th>
                                   <td width="70%" ' . $CSS_TABLE_SECONDARY_BODY_TD . '>' . $user->username . '</td>
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
        }
    }
}
