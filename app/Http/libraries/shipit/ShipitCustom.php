<?php
namespace Libraries\Shipit;

use Curl\Curl;
use Kattatzu\ShipIt\QuotationRequest;
use Kattatzu\ShipIt\ShipIt;
use Kattatzu\ShipIt\ShippingRequest;

class ShipitCustom
{
    private $shipit_email;
    private $shipit_token;
    private $shipit_environment;

    public function __construct($shipit_email, $shipit_token, $shipit_environment)
    {
        $this->shipit_email = $shipit_email;
        $this->shipit_token = $shipit_token;
        $this->shipit_environment = ($shipit_environment == 1)? Shipit::ENV_PRODUCTION : Shipit::ENV_DEVELOPMENT;
    }

    public function getRegions()
    {
        $data = [];
        $shipIt = new ShipIt($this->shipit_email, $this->shipit_token, $this->shipit_environment);
        foreach ($shipIt->getRegions() AS $region){
            $data[] = [
                'id' => $region->id,
                'number' => $region->number,
                'name' => $region->name
            ];
        }
        return $data;
    }

    public function getCommunes()
    {
        $data = [];
        $shipIt = new ShipIt($this->shipit_email, $this->shipit_token, $this->shipit_environment);
        foreach ($shipIt->getCommunes() AS $commune){
            if($commune->is_available == 1){
                $data[] = [
                    'id' => $commune->id,
                    'region_id' => $commune->region_id,
                    'code' => $commune->code,
                    'name' => $commune->name
                ];
            }
        }
        return $data;
    }

    public function getCommuneByName($communeName)
    {
        $data = [];
        $shipIt = new ShipIt($this->shipit_email, $this->shipit_token, $this->shipit_environment);
        foreach ($shipIt->getCommunes() AS $commune){
            if($commune->is_available == 1 && $communeName == $commune->name){
                $data = [
                    'id' => $commune->id,
                    'region_id' => $commune->region_id,
                    'code' => $commune->code,
                    'name' => $commune->name
                ];
            }
        }
        return $data;
    }

    public function getCourierPrices($request = array())
    {
        $end_point = 'http://api.shipit.cl/v/prices';
        $token = $this->shipit_token;
        $email = $this->shipit_email;

        $curl = new Curl();
        $curl->setHeader('Content-Type', 'application/json');
        $curl->setHeader('X-Shipit-Email', $email);
        $curl->setHeader('X-Shipit-Access-Token', $token);
        $curl->setHeader('Accept', 'application/vnd.shipit.v3');
        $curl->post($end_point, $request);

        if ($curl->error) {
            $message = ['error' => true, 'message' => 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage];
            return $message;
        } else {
            $array = json_decode(json_encode($curl->response), true);
            $message = ['error' => false, 'data' => $array];
            return $message;
        }
    }

    public function getTracking($id)
    {
        $shipIt = new ShipIt($this->shipit_email, $this->shipit_token, $this->shipit_environment);
        $shipping = $shipIt->getShipping($id);
        return $shipping;
    }

    public function getSizePacking(Order $order)
    {
        $shipIt = new ShipIt($this->shipit_email, $this->shipit_token, $this->shipit_environment);
        $size = $shipIt->getPackageSize($order->width, $order->height, $order->length);
        return $size;
    }

    public function generateOT(Order $order, $size)
    {
        $shipIt = new ShipIt($this->shipit_email, $this->shipit_token, $this->shipit_environment);
        $request = new ShippingRequest([
            'reference' => 'OC ' . $order->id,
            'full_name' => $order->business_name,
            'email' => $order->email,
            'items_count' => 1,
            'cellphone' => $order->phone,
            'is_payable' => false,
            'packing' => ShippingRequest::PACKING_NONE,
            'shipping_type' => ShippingRequest::DELIVERY_NORMAL,
            'destiny' => ShippingRequest::DESTINATION_HOME,
            'courier_for_client' => $order->shipit_courier_name,
            'approx_size' => $size,
            'address_commune_id' => $order->shipit_commune_id,
            'address_street' => $order->address,
            'address_number' => $order->address_number,
            'address_complement' => 'Depto/Oficina ' . $order->office_number,
        ]);

        $response =  $shipIt->requestShipping($request);
        return $response;
    }

}