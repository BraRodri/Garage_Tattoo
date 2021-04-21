<?php
class sapkupfer
{
    private $_rfc;

    public function __construct(){

        $this->_rfc = new saprfc(
            array(
                "logindata" => array(
                    "ASHOST" => SAP_HOST,
                    "SYSNR" => SAP_NUMSIS,
                    "CLIENT" => SAP_CLIENT,
                    "USER" => SAP_USERNAME,
                    "PASSWD" => SAP_PASSWORD,
                    "LANG" => "ES"
                ),
                "show_errors" => true,
                "debug" => false
            )
        );
    }

    public function __destruct(){

        $this->_rfc->logoff();
    }

    public function getStockPrecios($MATERIAL){

        $result = $this->_rfc->callFunction("ZSDFN_STK_PRECIO",
            array(
                array("IMPORT", "MATERIAL", $MATERIAL),
                array("EXPORT", "PRECIO", array()),
                array("EXPORT", "MONEDA", array()),
                array("EXPORT", "DESCRIP", array()),
                array("EXPORT", "UMEDIDA", array()),
                array("EXPORT", "PRESO_B", array()),
                array("EXPORT", "PESO_N", array()),
                array("EXPORT", "UNIM_P", array()),
                array("EXPORT", "VOLUMEN", array()),
                array("EXPORT", "UNIVOL", array()),
                array("EXPORT", "TAMANO", array()),
                array("TABLE", "T_STOCK", array()),
            )
        );

        if ($this->_rfc->getStatus() == SAPRFC_OK) {
            return $result;
        } else {
            $this->_rfc->printStatus();
            return array('error' => 1);
        }
    }
}
?>