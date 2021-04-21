<?php

namespace App\Http\Controllers;

use App\Models\Configurations;
use App\Models\Log;
use Application\Helper;
use Illuminate\Http\Request;

class ConfigurationsController extends Controller
{
    private $title = 'Parámetros Generales';
    private $parent_title = 'Configuración Global';
    private $module = 'configurations';

    public function index()
    {

        $configurations = Configurations::orderBy('id', 'desc')->get();

        return view('admvisch.configurations.index')->with(['title' => $this->title, 'parent_title' => $this->parent_title, 'module' => $this->module, 'configurations' => $configurations]);
    }

    public function enter()
    {
    }

    public function insert()
    {


        $author = (isset($_POST['author']) && !empty($_POST['author'])) ? Helper::postValue('author') : '';

        $post = array(
            'address' => Helper::postValue('address'),
            'city' => Helper::postValue('city'),
            'phone1' => Helper::postValue('phone1'),
            'phone2' => Helper::postValue('phone2'),
            'phone3' => Helper::postValue('phone3'),
            'phone4' => Helper::postValue('phone4'),
            'fax' => Helper::postValue('fax'),
            'email' => Helper::postValue('email'),
            'email2' => Helper::postValue('email2'),
            'email3' => Helper::postValue('email3'),
            'contact_email' => Helper::postValue('contact_email'),
            'sale_email' => Helper::postValue('sale_email'),
            'cotizacion_email' => Helper::postValue('cotizacion_email'),
            'suscription_email' => Helper::postValue('suscription_email'),
            'map' => Helper::postValue('map'),
            'horary' => Helper::postValue('horary'),
            'transfer_text' => Helper::postValue('transfer_text'),
            'webpay_text' => Helper::postValue('webpay_text'),
            'minimun_sale' => Helper::postValue('minimun_sale', 0),
            'minimum_free_shipping' => Helper::postValue('minimum_free_shipping', 0),
            'shipping_text' => Helper::postValue('shipping_text'),
            'shipping_text_for_paying' => Helper::postValue('shipping_text_for_paying'),
            'office_shipping_text' => Helper::postValue('office_shipping_text'),
            'discount_minimum' => Helper::postValue('discount_minimum', 0),
            'discount_percentage' => Helper::postValue('discount_percentage', 0),
            'shipping_active' => Helper::postValue('shipping_active', 0),
            'office_shipping_active' => Helper::postValue('office_shipping_active', 0),
            'transfer_active' => Helper::postValue('transfer_active', 0),
            'webpay_active' => Helper::postValue('webpay_active', 0),
            'social_facebook' => Helper::postValue('social_facebook'),
            'social_instagram' => Helper::postValue('social_instagram'),
            'social_linkedin' => Helper::postValue('social_linkedin'),
            'social_twitter' => Helper::postValue('social_twitter'),
            'social_youtube' => Helper::postValue('social_youtube'),
            'site_offline' => Helper::postValue('site_offline', 0),
            'shipping_type' => Helper::postValue('shipping_type'),
            'shipit_environment' => Helper::postValue('shipit_environment', 0),
            'shipit_email' => Helper::postValue('shipit_email'),
            'shipit_token' => Helper::postValue('shipit_token'),
            'shipit_tax' => Helper::postValue('shipit_tax', 0),
            'webpay_name_company' => Helper::postValue('webpay_name_company'),
            'webpay_code' => Helper::postValue('webpay_code'),
            'webpay_environment' => Helper::postValue('webpay_environment', 0),
            'webpay_private_key' => Helper::postValue('webpay_private_key'),
            'webpay_public_cert' => Helper::postValue('webpay_public_cert'),
            'webpay_tbk_cert' => Helper::postValue('webpay_tbk_cert'),
            'webpay_tax' => Helper::postValue('webpay_tax', 0),
            'active_tax' => Helper::postValue('active_tax', 0),
            'active_cart' => Helper::postValue('active_cart', 0),
            'active_cotizacion' => Helper::postValue('active_cotizacion', 0),
            'author' => $author
        );

        if ($insert = Configurations::create($post)) {
            $id = $insert->id;

            self::generateWebpayCerts($id);

            if (LOG_GENERATE === true) {
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'INGRESO',
                    'identifier' => $id,
                    'detail' => 'Ingresó nueva configuración "' . Helper::postValue('address') . '" con ID N°' . $id . '.'
                ]);
            }

            $this->redirect(URL_FRIENDLY_BASE . $this->module . '/index/success');
        } else {
            $this->redirect(URL_FRIENDLY_BASE . $this->module . '/enter/failure');
        }
    }

    public function edit($id)
    {

        $configuration = Configurations::findOrFail($id);
        return view('admvisch.configurations.edit')->with(['title' => $this->title, 'parent_title' => $this->parent_title, 'module' => $this->module, 'configuration' => $configuration]);
    }

    public function update()
    {

        $author = (isset($_POST['author']) && !empty($_POST['author'])) ? Helper::postValue('author') : '';

        $id = Helper::postValue('id');

        $post = array(
            'address' => Helper::postValue('address'),
            'city' => Helper::postValue('city'),
            'address_2' => Helper::postValue('address_2'),
            'city_2' => Helper::postValue('city_2'),
            'phone1' => Helper::postValue('phone1'),
            'phone2' => Helper::postValue('phone2'),
            'phone3' => Helper::postValue('phone3'),
            'phone4' => Helper::postValue('phone4'),
            'fax' => Helper::postValue('fax'),
            'email' => Helper::postValue('email'),
            'email2' => Helper::postValue('email2'),
            'email3' => Helper::postValue('email3'),
            'contact_email' => Helper::postValue('contact_email'),
            'sale_email' => Helper::postValue('sale_email'),
            'suscription_email' => Helper::postValue('suscription_email'),
            'cotizacion_email' => Helper::postValue('cotizacion_email'),
            'map_1' => Helper::postValue('map_1'),
            'map_2' => Helper::postValue('map_2'),
            'map_1_link' => Helper::postValue('map_1_link'),
            'map_2_link' => Helper::postValue('map_2_link'),
            'horary' => Helper::postValue('horary'),
            'tranfer_text' => Helper::postValue('transfer_text'),
            'webpay_text' => Helper::postValue('webpay_text'),
            'minimum_sale' => Helper::postValue('minimun_sale', 0),
            'minimum_free_shipping' => Helper::postValue('minimum_free_shipping', 0),
            'shipping_text' => Helper::postValue('shipping_text'),
            'shipping_text_for_paying' => Helper::postValue('shipping_text_for_paying'),
            'office_shipping_text' => Helper::postValue('office_shipping_text'),
            'discount_minimum' => Helper::postValue('discount_minimum', 0),
            'discount_percentage' => Helper::postValue('discount_percentage', 0),
            'shipping_active' => Helper::postValue('shipping_active', 0),
            'office_shipping_active' => Helper::postValue('office_shipping_active', 0),
            'transfer_active' => Helper::postValue('transfer_active', 0),
            'webpay_active' => Helper::postValue('webpay_active', 0),
            'social_facebook' => Helper::postValue('social_facebook'),
            'social_instagram' => Helper::postValue('social_instagram'),
            'social_linkedin' => Helper::postValue('social_linkedin'),
            'social_twitter' => Helper::postValue('social_twitter'),
            'social_youtube' => Helper::postValue('social_youtube'),
            'site_offline' => Helper::postValue('site_offline', 0),
            'shipping_type' => Helper::postValue('shipping_type'),
            'shipit_environment' => Helper::postValue('shipit_environment', 0),
            'shipit_email' => Helper::postValue('shipit_email'),
            'shipit_token' => Helper::postValue('shipit_token'),
            'shipit_tax' => Helper::postValue('shipit_tax', 0),
            'webpay_name_company' => Helper::postValue('webpay_name_company'),
            'webpay_code' => Helper::postValue('webpay_code'),
            'webpay_environment' => Helper::postValue('webpay_environment', 0),
            'webpay_private_key' => Helper::postValue('webpay_private_key'),
            'webpay_public_cert' => Helper::postValue('webpay_public_cert'),
            'webpay_tbk_cert' => Helper::postValue('webpay_tbk_cert'),
            'webpay_tax' => Helper::postValue('webpay_tax', 0),
            'active_tax' => Helper::postValue('active_tax', 0),
            'active_cart' => Helper::postValue('active_cart', 0),
            'active_cotizacion' => Helper::postValue('active_cotizacion', 0),
            'author' => $author
        );

        if ($update = Configurations::findOrFail($id)->update($post)) {
            //self::generateWebpayCerts($id);

            if (LOG_GENERATE === true) {
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ACTUALIZACION',
                    'identifier' => $id,
                    'detail' => 'Actualizó configuración "' . Helper::postValue('address') . '" con ID N°' . $id . '.'
                ]);
            }

            session()->flash('error', 'success');
            return redirect()->route('configurations.edit', $id);
        } else {
            session()->flash('error', 'failure');
            return redirect()->route('configurations.edit', $id);
        }
    }

    public function delete($id)
    {

        $this->_acl->accessGlobal($this->module, 'DELETE');

        $configuration = Configurations::findOrFail($id);

        if ($delete = Configurations::findOrFail($id)->delete()) {

            if (LOG_GENERATE === true) {
                Log::create([
                    'id_user' => Helper::sessionSystemValue('id'),
                    'date' => Helper::getDate($hour = false),
                    'hour' => Helper::getHour(),
                    'ip' => Helper::getIP(),
                    'module' => $this->module,
                    'action' => 'ELIMINAR',
                    'identifier' => $id,
                    'detail' => 'Eliminó configuración "' . $configuration->address . '" con ID N°' . $id . '.'
                ]);
            }

            $this->redirect(URL_FRIENDLY_BASE . $this->module . '/index/success');
        } else {
            $this->redirect(URL_FRIENDLY_BASE . $this->module . '/index/failure');
        }
    }

    public function generateWebpayCerts($id)
    {

        $configuration = Configurations::findOrFail($id);
        if ($configuration) {

            $webpay_environment = ($configuration->webpay_environment == 0) ? 'webpay-plus-integration' : 'webpay-plus-production';
            $directoryUploadCerts = str_replace('\admvisch\\', '', str_replace('/admvisch/', '', ROOT)) . DS . 'webpay' . DS . 'vendor' . DS . 'freshwork' . DS . 'transbank' . DS . 'src' . DS . 'certs' . DS . $webpay_environment . DS;

            $archiveWebpayPrivateKey = $directoryUploadCerts . $configuration->webpay_code . '.key';
            $archiveWebpayPublicCert = $directoryUploadCerts . $configuration->webpay_code . '.crt';
            $archiveWebpayTbkCert = $directoryUploadCerts . 'serverTBK.crt';

            if (file_exists($archiveWebpayPrivateKey) == true) {
                @unlink($archiveWebpayPrivateKey);
            }
            if (file_exists($archiveWebpayPublicCert) == true) {
                @unlink($archiveWebpayPublicCert);
            }
            if (file_exists($archiveWebpayTbkCert) == true) {
                @unlink($archiveWebpayTbkCert);
            }

            if (!file_exists($archiveWebpayPrivateKey) == true) {
                $archive = fopen($archiveWebpayPrivateKey, "w");
                if ($archive == true) {
                    fwrite($archive, $configuration->webpay_private_key);
                }
                fclose($archive);
            }
            if (!file_exists($archiveWebpayPublicCert) == true) {
                $archive = fopen($archiveWebpayPublicCert, "w");
                if ($archive == true) {
                    fwrite($archive, $configuration->webpay_public_cert);
                }
                fclose($archive);
            }
            if (!file_exists($archiveWebpayTbkCert) == true) {
                $archive = fopen($archiveWebpayTbkCert, "w");
                if ($archive == true) {
                    fwrite($archive, $configuration->webpay_tbk_cert);
                }
                fclose($archive);
            }
        }

        return true;
    }
}
