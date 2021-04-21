<?php

/**
 * Created by PhpStorm.
 * User: Ignacio Lincofil Briones <ilincofil@gmail.com>
 * Date: 05-04-2017
 * Time: 17:31
 */
class Indicator
{
    private $api_url = 'http://mindicador.cl/api';
    private $connector;
    private $indicator;

    public function __construct()
    {
        if(ini_get('allow_url_fopen')){
            try {
                $this->connector = file_get_contents($this->api_url);
            } catch(Exception $e){
                throw new Exception('Error: ' . $e->getMessage());
            }
        } else {
            try {
                $curl = curl_init($this->api_url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $this->connector = curl_exec($curl);
                curl_close($curl);
            } catch(Exception $e){
                throw new Exception('Error: ' . $e->getMessage());
            }
        }
    }

    public function getIndicators()
    {
        $this->indicator = json_decode($this->connector);
        return $this->indicator;
    }
}