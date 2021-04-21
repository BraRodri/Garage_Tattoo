<?php
/**
 * Created by PhpStorm.
 * User: Ignacio Lincofil Briones <ilincofil@gmail.com>
 * Date: 21-03-2017
 * Time: 13:09
 */
namespace Application;

use Libraries\Upload\Upload;

class Helper
{
    // Retorna array con formato para ser interpretado

    public static function printArrayToReadable($var)
    {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }

    public static function printXMLToReadable($var)
    {
        echo '<pre>';
        echo htmlentities($var);
        echo '</pre>';
    }

    // Retorna fecha en formato usuario

    public static function dateFormatUser($var, $hour = true)
    {
        if($hour == true) {
            return date("d-m-Y H:i:s", strtotime($var));
        } else {
            return date("d-m-Y", strtotime($var));
        }
    }

    public static function dateFormatSystem($var) {
        $date = explode("-", $var);
        $day = current($date);
        $month = next($date);
        $year = next($date);

        return $year . '-' . $month . '-' . $day;
    }

    public static function dateDiferenceByArgument($var, $argument = '-1 day'){
        $nuevafecha = strtotime($argument, strtotime($var));
        $nuevafecha = date('Y-m-d', $nuevafecha);
        return $nuevafecha;
    }

    // Quita las barras de un string con comillas escapadas

    public static function customStripslashes($value)
    {
       
            if (is_array($value)) {
                $value = array_map("customStripslashes", $value);
            } else {
                $value = stripslashes($value);
            }
        
        return $value;
    }

    // Retorna valor de variables GET

    public static function postValue($varName, $default = null, $emptyDefault = false)
    {
        $value = $default;
        if (isset($_POST[$varName])) {
            $value = $_POST[$varName];
            if ($emptyDefault && empty($value)) {
                return $default;
            }
        }
        $value = self::customStripslashes($value);
        return $value;
    }

    // Retorna valor de variables POST

    public static function getValue($varName, $default = null, $emptyDefault = false)
    {
        $value = $default;
        if (isset($_GET[$varName])) {
            $value = $_GET[$varName];
            if ($emptyDefault && empty($value)) {
                return $default;
            }
        }
        $value = self::customStripslashes($value);
        return $value;
    }

    // Retorna valor de variables COOKIE

    public static function cookieValue($varName, $default = null, $emptyDefault = false)
    {
        $value = $default;
        if (isset($_COOKIE[$varName])) {
            $value = $_COOKIE[$varName];
            if ($emptyDefault && empty($value)) {
                return $default;
            }
        }
        $value = self::customStripslashes($value);
        return $value;
    }

    // Retorna valor de variables SERVER

    public static function serverValue($varName, $default = null, $emptyDefault = false)
    {
        $value = $default;
        if (isset($_SERVER[$varName])) {
            $value = $_SERVER[$varName];
            if ($emptyDefault && empty($value)) {
                return $default;
            }
        }
        return $value;
    }

    // Retorna valor de variables SESSION

    public static function sessionSystemValue($varName, $default = null, $emptyDefault = false) {
        $value = $default;
        if (isset($_SESSION[SESSION_NAME][$varName])) {
            $value = $_SESSION[SESSION_NAME][$varName];
            if ($emptyDefault && empty($value)) {
                return $default;
            }
        }
        return $value;
    }

    public static function sessionUserValue($varName, $default = null, $emptyDefault = false) {
        $value = $default;
        if (isset($_SESSION['DEFAULT'][$varName])) {
            $value = $_SESSION['DEFAULT'][$varName];
            if ($emptyDefault && empty($value)) {
                return $default;
            }
        }
        return $value;
    }

    // Obtiene el tamaño en bytes a partir de un string

    public static function strToSize($size = 0)
    {
        if (!$size) {
            return 0;
        }
        $numbers = "";
        $letters = "";
        $size = trim("$size");
        for ($i = 0, $c = strlen($size); $i < $c; $i++) {
            if (is_numeric($size[$i])) {
                $numbers.= $size[$i];
            } else {
                $letters.= $size[$i];
            }
        }
        $numbers = intval($numbers, 10);
        if (empty($numbers)) {
            return 0;
        }
        if (empty($letters)) {
            return $size;
        }
        $mult = 0;
        if (strlen($letters) > 2) {
            $letters = strlen($letters, 0, 2);
        }

        switch (strtolower($letters)) {
            case "g":
            case "gb":
                $mult = 1073741824;
                break;
            case "m":
            case "mb":
                $mult = 1048576;
                break;
            case "k":
            case "kb":
                $mult = 1024;
                break;
            case "b":
                $mult = 1;
                break;
            default:
                $mult = 1;
                break;
        }
        return ($mult * $numbers);
    }

    // Retorna el tamaño máximo de un archivo a subir

    public static function maxUploadSize()
    {
        $postmax = 0;
        $uploadmax = 0;

        if (!($uploadmax = ini_get("upload_max_filesize"))) {
            $uploadmax = "2M";
        }

        if ($postmax == ini_get("post_max_size")) {
            return min(self::strToSize($postmax), self::strToSize($uploadmax));
        } else {
            return self::strToSize($uploadmax);
        }
    }

    //

    public static function uploadSizeUser()
    {
        $val = trim(ini_get("upload_max_filesize"));
        $last = strtolower($val[strlen($val)-1]);
        switch($last) {
            case 'g':
                $val = (int)$val . " Gyga Bytes";
                break;
            case 'm':
                $val = (int)$val . " Mega Bytes";
                break;
            case 'k':
                $val = (int)$val . " Kilo Bytes";
                break;
        }
        return $val;
    }

    // Lee un directorio y retorna los directorios en el

    public static function leerDirectorios($dir)
    {
        $dlist = array();
        if ($dirs = opendir($dir)) {
            while (($d = readdir($dirs)) !== false) {
                if (($d != ".") && ($d != "..") && is_dir($dir.$d)) {
                    $dlist[$d] = $d;
                }
            }
            closedir($dirs);
            asort($dlist);
            reset($dlist);
        }
        return $dlist;
    }

    // Lee un directorio y retorna los archivos en el en forma de arreglo

    public static function leerArchivos($dir)
    {
        $flist = array();
        if ($dirs = opendir($dir)) {
            while (($f = readdir($dirs)) !== false) {
                if (($f != ".") && ($f != "..") && is_file($dir.$f)) {
                    $flist[$f] = $f;
                }
            }
            closedir($dirs);
            asort($flist);
            reset($flist);
        }
        return $flist;
    }

    // Retorna la fecha y hora actual

    public static function getDate($hora = true)
    {
        if($hora === true){
            return date("Y-m-d H:i:s");
        } else {
            return date("Y-m-d");
        }
    }

    // Retorna la hora actual

    public static function getHour()
    {
        return date("H:i:s");
    }

    public static function getDateSQL($hora = true)
    {
        if($hora === true){
            return date("Y-m-d H:i:s.u");
        } else {
            return date("Y-m-d");
        }
    }

    public static function getDateInt()
    {
        $current_date = \DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
        $format_date = $current_date->format("YmdHisu");
        return $format_date;
    }

    // Retorna la IP del usuario

    public static function getIP()
    {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) $ip = getenv("REMOTE_ADDR");
        else if (isset($_SERVER["REMOTE_ADDR"]) && $_SERVER["REMOTE_ADDR"] && strcasecmp($_SERVER["REMOTE_ADDR"], "unknown")) $ip = $_SERVER["REMOTE_ADDR"];
        else $ip = "unknown";
        return ($ip);
    }

    // Verifica si una dirección de email es correcta

    public static function verifyEmail($email, $convert = false)
    {
        if (mb_eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$", $email)) {
            if ($convert) {
                $email = str_replace("@", " at ", $email);
                $email = str_replace(".", " dot ", $email);
            }
            return $email;
        }
        return false;
    }

    // Verifica si variable es numerico

    public static function verifyNumber($input)
    {
        if (preg_match("/^[0-9]+$/", $input)) {
            return true;
        }
        return false;
    }

    // Verifica si variable es alfanumerico

    public static function verifyAlphaNumeric($input)
    {
        if (preg_match("/^[a-zA-Z0-9áéíóúüñÁÉÍÓÚÜÑ(),. ]*$/", $input)) {
            return true;
        }
        return false;
    }

    // Verifica si variable es letras

    public static function verifyLetters($input)
    {
        if (preg_match("/^[a-zA-ZáéíóúüñÁÉÍÓÚÜÑ]+$/", $input)) {
            return true;
        }
        return false;
    }

    public static function formatRut($rut)
    {
        /*if(strlen($rut) > 7 && strlen($rut) < 10) {
            return number_format(substr($rut, 0, -1), 0, "", ".") . '-' . substr($rut, strlen($rut) - 1, 1);
        } else {
            return $rut;
        }*/

        if(strlen($rut) == 10) {
            $parte1 = substr($rut, 1, 2); //12
            $parte2 = substr($rut, 3, 3); //345
            $parte3 = substr($rut, 6, 3); //456
            $parte4 = substr($rut, 9);   //todo despues del caracter 8

            return $parte1 . "." . $parte2 . "." . $parte3 . "-" . $parte4;
        } else if(strlen($rut) == 9) {
            $parte1 = substr($rut, 0, 2); //12
            $parte2 = substr($rut, 2, 3); //345
            $parte3 = substr($rut, 5, 3); //456
            $parte4 = substr($rut, 8);   //todo despues del caracter 8

            return $parte1 . "." . $parte2 . "." . $parte3 . "-" . $parte4;
        } else if(strlen($rut) == 8) {
            $parte1 = substr($rut, 0, 1); //9
            $parte2 = substr($rut, 1, 3); //345
            $parte3 = substr($rut, 4, 3); //456
            $parte4 = substr($rut, 7);   //todo despues del caracter 8

            return $parte1 . "." . $parte2 . "." . $parte3 . "-" . $parte4;
        } else {
            return $rut;
        }
    }

    // Verifica si un RUT es correcto

    public static function verifyRut($rut)
    {
        $validChars = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "k", "K");
        $rut = str_replace("-", "", $rut);
        $rut = str_replace(".", "", $rut);
        $rut = str_replace(" ", "", $rut);
        if (strlen($rut) > 2) {
            for ($i = 0; $i < strlen($rut); $i++) {
                if (!in_array($rut [
                $i
                ], $validChars)) {
                    return false;
                }
            }
            $dv = strtolower(substr($rut, -1));
            $rut = substr($rut, 0, strlen($rut) - 1);
            if (is_numeric($rut)) {
                $m = 2;
                $total = 0;
                for ($i = strlen($rut) - 1; $i >= 0; $i--) {
                    $total = $total + intval($rut [
                        $i
                    ], 10) * $m;
                    if ($m == 7) {
                        $m = 2;
                    } else {
                        $m++;
                    }
                }
                $res = 11 - ($total % 11);
                $dvr = "0";
                if ($res == 10) {
                    $dvr = "k";
                } else if ($res == 11) {
                    $dvr = "0";
                } else {
                    $dvr = $res;
                }
                if ($dvr == $dv) {
                    return self::formatRut($rut . $dv);
                }
            }
        }
        return false;
    }

    // Retorna la cantidad de días entre un periodo expresado en fechas

    public static function numberDaysByDates($date_end, $date_start)
    {
        $f0 = strtotime($date_end);
        $f1 = strtotime($date_start);
        if ($f0 < $f1) {
            $tmp = $f1;
            $f1 = $f0;
            $f0 = $tmp;
        }
        return ($f0 - $f1) / (60 * 60 * 24);
    }

    // Reemplaza acentos y caracteres de cadena
    public static function clearStringAccentsAndOthers($text)
    {
        $patron = array (
            'Á' => 'A',
            'É' => 'E',
            'Í' => 'I',
            'Ó' => 'O',
            'Ú' => 'U',
            'Ñ' => 'N',

        );

        $text = str_replace(array_keys($patron),array_values($patron),$text);
        return $text;
    }

    // Retorna cadena de texto con acentos en minusculas

    public static function transformAccentsLower($text)
    {
        $patron = array (
            'Á' => 'á',
            'É' => 'é',
            'Í' => 'í',
            'Ó' => 'ó',
            'Ú' => 'ú',
            'Ñ' => 'ñ',

        );

        $text = str_replace(array_keys($patron),array_values($patron),$text);
        return $text;
    }

    // Retorna cadena de texto con acentos en mayusuculas

    public static function transformAccentsUpper($text)
    {
        $patron = array (
            'á' => 'Á',
            'é' => 'É',
            'í' => 'Í',
            'ó' => 'Ó',
            'ú' => 'Ú',
            'ñ' => 'Ñ',

        );

        $text = str_replace(array_keys($patron),array_values($patron),$text);
        return $text;
    }

    // Verifica si la palabra tiene más de 3 letras para transformarla a su inicial en mayusculas
    public static function transformUCWord($text)
    {
        $array = explode(' ', $text);
        foreach($array as $k => $v) {
            if(strlen($v) >= 3) {
                $array[$k] = ucwords($v);
            } else {
                $array[$k] = $v;
            }
        }
        $text = implode(' ', $array);
        return $text;
    }

    // Retorna cadena de texto a mayusculas

    public static function transformUpperTextToLowerText($text)
    {
        return mb_strtoupper(self::transformAccentsUpper($text), 'UTF-8');
    }

    // Retorna cadena de texto a minusculas

    public static function transformLowerTextToUpperText($text)
    {
        return mb_strtolower(self::transformAccentsLower($text), 'UTF-8');
    }

    // Retorna atributos de una imagen

    public static function propertyImage($image, $option)
    {
        /*
        El indice 0 contiene el ancho de la imagen en pixeles.
        El indice 1 contiene la altura.
        El indice 2 es una bandera que indica el tipo de imagen:
        1 = GIF,
        2 = JPG,
        3 = PNG,
        4 = SWF,
        5 = PSD,
        6 = BMP,
        7 = TIFF(orden de bytes intel),
        8 = TIFF(orden de bytes motorola),
        9 = JPC,
        10 = JP2,
        11 = JPX,
        12 = JB2,
        13 = SWC,
        14 = IFF,
        15 = WBMP,
        16 = XBM.

        Estos valores corresponden a las constantes IMAGETYPE que fueron agregadas en PHP 4.3.0.
        El indice 3 es una cadena de texto con el valor correcto height="yyy" width="xxx" que puede ser usado directamente en una etiqueta IMG.
        */

        $properties = getimagesize($image);
        $width = current($properties);
        $high = next($properties);
        $type = next($properties);

        switch ($type) {
            case 1:
                $type_name = "GIF";
                break;
            case 2:
                $type_name = "JPG";
                break;
            case 3:
                $type_name = "PNG";
                break;
            case 4:
                $type_name = "SWF";
                break;
            case 5:
                $type_name = "PSD";
                break;
            case 6:
                $type_name = "BMP";
                break;
            case 7:
            case 8:
                $type_name = "TIFF";
                break;
            case 9:
                $type_name = "JPC";
                break;
            case 10:
                $type_name = "JP2";
                break;
            case 11:
                $type_name = "JPX";
                break;
            case 12:
                $type_name = "JB2";
                break;
            case 13:
                $type_name = "SWC";
                break;
            case 14:
                $type_name = "IFF";
                break;
            case 15:
                $type_name = "WBMP";
                break;
            case 16:
                $type_name = "XBM";
                break;
            default:
                $type_name = "No encontrado";
                break;
        }
        switch ($option) {
            case "width":
                return $width;
                break;
            case "high":
                return $high;
                break;
            case "type":
                return $type_name;
                break;
            default:
                return "";
        }
    }

    // Retorna texto con longitud determinada por usuario

    public static function shortenText($text, $length = null, $point = false)
    {
        if ($length != null) {
            if (strlen($text) > $length) {
                if($point === true){
                    $text = substr($text, 0, $length) . "..";
                } else {
                    $text = substr($text, 0, $length);
                }
            } else {
                $text = $text;
            }
            return $text;
        }
    }

    // Retorna cadena sin formato HTML

    public static function removeFormatHtml($text)
    {
        return strip_tags($text);
    }

    // Retorna nombre de mes a partir de su número

    public static function monthToMonthName($month, $language = 'spanish')
    {
        setlocale(LC_TIME, $language);
        $month_name = strftime("%B", mktime(0, 0, 0, $month, 1, 2000));
        return $month_name;
    }

    // Retorna entero con formato de miles de pesos

    public static function formatInteger($value)
    {
        return number_format(intval($value), 0, '', '.');
    }

    // Retorna decimales con formato

    public static function formatDecimals($value, $decimal = 2)
    {
        return number_format($value, $decimal, ',', '.');
    }

    // Retorna cadena a formato de url amigable

    public static function friendlyUrl($url)
    {
        // Tranformamos todo a minusculas
        $url = mb_strtolower($url, 'UTF-8');
        //Rememplazamos caracteres especiales latinos
        $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
        $repl = array('a', 'e', 'i', 'o', 'u', 'n');
        $url = str_replace ($find, $repl, $url);
        // Añadimos los guiones
        $find = array(' ', '&', '\r\n', '\n', '+');
        $url = str_replace ($find, '-', $url);
        // Eliminamos y Reemplazamos demás caracteres especiales
        $find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
        $repl = array('', '-', '');
        $url = preg_replace ($find, $repl, $url);
        return $url;
    }

    // Retorna transformación de URL simple a URL amigable
    public static function transformURLtoFriendly($url)
    {
        $new_url = '';

        if(!empty($url))
        {
            $partes_url = explode("?", $url);
            $new_url = current($partes_url);
            $arguments = explode("&", end($partes_url));

            foreach ($arguments AS $value)
            {
                $new_value = explode("=", $value);
                $new_url .= "/" . end($new_value);
            }
        }

        return $new_url;
    }

    // Retorna el número de un mes

    public static function monthToMonthNumber($month)
    {
        $months = array(
            "Enero" => "01",
            "Febrero" => "02",
            "Marzo" => "03",
            "Abril" => "04",
            "Mayo" => "05",
            "Junio" => "06",
            "Julio" => "07",
            "Agosto" => "08",
            "Septiembre" => "09",
            "Octubre" => "10",
            "Noviembre" => "11",
            "Diciembre" => "12"
        );
        return $months[$month];
    }

    // Retorna una fecha con formato de nombre de dia mes y año

    public static function formatDateToCompleteDateUser($datetime, $withHour = false, $language = 'spanish')
    {
        $array_date_time = explode(" ", $datetime);
        $date = current($array_date_time);

        if(count($array_date_time) > 1){
            $hour = end($array_date_time);
            $array_hour = explode(":", $hour);
            $hour = current($array_hour);
            $minute = next($array_hour);
            $second = end($array_hour);
        }
        $array_date = explode("-", $date);
        $year = current($array_date);
        $month = next($array_date);
        $day = end($array_date);

        $date_to_strtotime = strtotime($date);

        switch (date('w', $date_to_strtotime)){
            case 0: $name_day = "Domingo"; break;
            case 1: $name_day = "Lunes"; break;
            case 2: $name_day = "Martes"; break;
            case 3: $name_day = "Miércoles"; break;
            case 4: $name_day = "Jueves"; break;
            case 5: $name_day = "Viernes"; break;
            case 6: $name_day = "Sábado"; break;
        }

        setlocale(LC_TIME, $language);

        if(count($array_date) > 1){
            if($withHour == true)
            {
                $my_date = gmmktime($hour, $minute, $second, $month, $day, $year);
                return $name_day . ", " . strftime("%d de", $my_date) . " " . self::monthToMonthName($month) . " " . strftime("de %Y | %H:%M:%S", $my_date);
            } else {
                $my_date = gmmktime(0, 0, 0, $month, $day, $year);
                return $name_day . ", " . strftime("%d de", $my_date) . " " . self::monthToMonthName($month) . " " . strftime("de %Y", $my_date);
            }
        } else {
            $my_date = gmmktime(0, 0, 0, $month, $day, $year);
            return $name_day . ", " . strftime("%d de", $my_date) . " " . self::monthToMonthName($month) . " " . strftime("de %Y", $my_date);
        }
    }

    // Retorna una cadena de texto aleatoria

    public static function randomString($length = 8, $lc = TRUE, $uc = FALSE, $n = TRUE, $sc = FALSE)
    {
        $source = "";

        if ($lc == 1)
            $source .= 'abcdefghijklmnopqrstuvwxyz';
        if ($uc == 1)
            $source.= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ($n == 1)
            $source.= '1234567890';
        if ($sc == 1)
            $source.= '|@#~$%()=^*+[]{}-_';
        if ($length > 0) {
            $rstr = "";
            $source = str_split($source, 1);
            for ($i = 1; $i <= $length; $i++) {
                mt_srand((double) microtime() * 1000000);
                $num = mt_rand(1, count($source));
                $rstr.= $source[$num - 1];
            }
        }
        return $rstr;
    }

    // Retorna el peso de un archivo

    public static function sizeArchive($bytes, $decimals = 2) {
        $size = array('B','KB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }

    public static function toISO($cadena){
        //convierte de UTF-8 a ISO-8859-1
        $cadena = mb_convert_encoding($cadena, "ISO-8859-1", "UTF-8");
        return $cadena;
    }

    public static function toUTF($cadena){
        //convierte de ISO-8859-1 a UTF-8
        $cadena = mb_convert_encoding($cadena, "UTF-8", "ISO-8859-1");
        return $cadena;
    }

    public static function arrayToObject($array){
        if(!is_array($array)) {
            return $array;
        }

        $object = new \stdClass();
        if (is_array($array) && count($array) > 0) {
            foreach ($array as $name=>$value) {
                $name = trim($name);
                if (!empty($name)) {
                    $object->$name = self::arrayToObject($value);
                }
            }
            return $object;
        }
        else {
            return FALSE;
        }
    }

    public static function addActiveSidebarMenu($match = false){

        $r = new Request();

        $activeClass = '';

        if($r->getController() != false){
            if($r->getController() == $match){
                $activeClass = 'class="active"';
            } else if($r->getController() . '/' . $r->getMethod() == $match){
                $activeClass = 'class="active"';
            }
        }

        return $activeClass;
    }

    public static function addActiveSidebarSubMenu($match = array()){

        $r = new Request();

        $activeClass = '';

        if($r->getController() != false){
            if(in_array($r->getController(), $match)){
                $activeClass = 'active opened';
            } else if(in_array($r->getController() . '/' . $r->getMethod(), $match)){
                $activeClass = 'active opened';
            }
        }

        return $activeClass;
    }

    public static function in_array_r($needle, $haystack, $strict = false) {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && self::in_array_r($needle, $item, $strict))) {
                return true;
            }
        }

        return false;
    }

    public static function is_multi($a) {
        $rv = array_filter($a,'is_array');
        if(count($rv)>0) return true;
        return false;
    }

    public static function checkPermission($array_roles_permissions, $permission, $action){

        if(Helper::in_array_r($permission, $array_roles_permissions)){
            if(in_array($action, $array_roles_permissions[$permission]['actions'])){
                return true;
            }
        }

        return false;
    }

    public static function createDirectoryUpload($path, $create = false)
    {
        if($create == false) {
            return false;
        }

        $directoryPath = (substr($path , -1) == '\\')? $path : $path;

        if(!file_exists($directoryPath))
            mkdir($directoryPath);
    }

    public static function uploadImage($directoryUpload, $inputName){

        if (Upload::formIsSubmitted() && isset($_FILES) && $_FILES[$inputName]['size'] > 0) {
            $upload = new Upload($inputName);
            $upload->setDirectory($directoryUpload)->create(true);

            $upload->addRules([
                'size' => self::maxUploadSize(),
                'extensions' => 'jpg|png',
            ])->customErrorMessages([
                'size' => 'Sólo puede subir archivos de menos de ' . self::uploadSizeUser() . ' de tamaño.',
                'extensions' => 'Sólo se puede subir archivos jpg y png.'
            ]);

            $upload->encryptFileNames(true)->only('jpg|png');

            $upload->start();

            if ($upload->unsuccessfulFilesHas()) {
                if ($upload->displayErrors()) {
                    return 'error';
                }
            }

            if ($upload->successfulFilesHas()) {
                foreach ($upload->successFiles() as $file) {
                    return $file->encryptedName;
                }
            }
        }
    }

    public static function uploadPdf($directoryUpload, $inputName){

        if (Upload::formIsSubmitted() && isset($_FILES) && $_FILES[$inputName]['size'] > 0) {
            $upload = new Upload($inputName);
            $upload->setDirectory($directoryUpload)->create(true);

            $upload->addRules([
                'size' => self::maxUploadSize(),
                'extensions' => 'pdf',
            ])->customErrorMessages([
                'size' => 'Sólo puede subir archivos de menos de ' . self::uploadSizeUser() . ' de tamaño.',
                'extensions' => 'Sólo se puede subir archivos pdf.'
            ]);

            $upload->encryptFileNames(true)->only('pdf');

            $upload->start();

            if ($upload->unsuccessfulFilesHas()) {
                if ($upload->displayErrors()) {
                    return 'error';
                }
            }

            if ($upload->successfulFilesHas()) {
                foreach ($upload->successFiles() as $file) {
                    return $file->encryptedName;
                }
            }
        }
    }

    public static function deleteArchive($directoryUpload, $imagen){

        if(!empty($imagen)){
            $directoryArchive = $directoryUpload . '/' . $imagen;

            if(file_exists($directoryArchive)){
                @unlink($directoryArchive);
            }
        }
        return true;
    }

    public static function getImageOrImageDefault($image, $subDirectory = false, $imageDefault = 'images\contenido-no-disponible.jpg', $tumb = false)
    {
        if(!empty($image)){
            $current_image = ROOT_WEB . 'upload' . DS . $subDirectory . DS . 'img' . DS . $image;

            if(!file_exists($current_image)){
                $current_image  = $imageDefault;
            } else {
                if($tumb === true){
                    $current_image = 'upload' . DS . $subDirectory . DS . 'img' . DS . $image;
                } else {
                    $current_image = BASE_URL_ROOT . 'upload' . DS . $subDirectory . DS . 'img' . DS . $image;
                }
            }
        } else {
            $current_image  = $imageDefault;
        }

        $current_image = str_replace('\\', '/', $current_image);
        return $current_image;
    }

    public static function clearStringByAlt($s)
    {
        //Quitando Caracteres Especiales
        $s= str_replace('"', '', $s);
        $s= str_replace(':', '', $s);
        $s= str_replace('.', '', $s);
        $s= str_replace(',', '', $s);
        $s= str_replace(';', '', $s);
        $s= str_replace('–', '', $s);
        return $s;
    }

    public static function getMetaDescription($text)
    {
        $text = strip_tags(self::clearStringByAlt(preg_replace("/ +/"," ",$text)));

        $words = explode(' ', $text);
        foreach($words as $key => $value)
        {
            if(!empty($value) && strlen($value) > 0){
                $words[$key] = trim($value);
            }
        }
        $new_string = implode(' ', $words);

        $text = $new_string;
        $text = trim($text);
        $text = substr($text, 0, 247);
        $text = preg_replace("/ +/"," ",$text);
        return $text."...";
    }

    public static function getMetaKeywords($text)
    {
        // Limpiamos el texto
        $text = strip_tags(self::clearStringByAlt($text));
        $text = mb_strtolower($text, 'UTF-8');
        $text = trim($text);
        $text = preg_replace('/[^a-zA-Z0-9 -]/', ' ', $text);
        // extraemos las palabras
        $match = explode(" ", $text);
        // contamos las palabras
        $count = array();
        if (is_array($match)) {
            foreach ($match as $key => $val) {
                if (strlen($val) > 3) {
                    if (isset($count[$val])) {
                        $count[$val]++;
                    } else {
                        $count[$val] = 1;
                    }
                }
            }
        }
        // Ordenamos los totales
        arsort($count);
        $count = array_slice($count, 0, 10);
        return implode(", ", array_keys($count));
    }

    public static function generateFormToken($form)
    {
        // Generación del ID Token
        $hora = date('H:i');
        $session_id = session_id();
        $token = hash('sha256', $hora . $session_id);

        // Generación de la fecha del ID Token
        $token_time = time();

        // Sesión para poder comprobar su validez cuando se reciba token desde un formulario
        $_SESSION['csrf'][$form . '_token'] = array('token' => $token, 'time' => $token_time);

        return $token;
    }

    public static function verifyFormToken($form, $token, $delta_time=0)
    {
        // Comprueba si hay un token registrado en sesión para el formulario
        if(!isset($_SESSION['csrf'][$form.'_token'])) {
            return false;
        }

        // Compara el token recibido con el registrado en sesión
        if ($_SESSION['csrf'][$form.'_token']['token'] !== $token) {
            return false;
        }

        // Si se indica un tiempo máximo de validez del ticket se compara la fecha actual con la de generación del ticket
        if($delta_time > 0){
            $token_age = time() - $_SESSION['csrf'][$form . '_token']['time'];
            if($token_age >= $delta_time){
                return false;
            }
        }

        return true;
    }

    public static function getDataStatusShippingAndOfficeShipping()
    {
        $data = array(
            1 => "Pendiente",
            2 => "Compra Aprobada",
            3 => "En Proceso",
            4 => "Enviado / Listo para Retiro",
            5 => "Entregado"
        );
        return $data;
    }

    public static function getDataStatusShipping()
    {
        $data = array(
            1 => "Pendiente",
            2 => "Compra Aprobada",
            3 => "En Proceso",
            4 => "Enviado",
            5 => "Entregado"
        );
        return $data;
    }

    public static function getDataStatusOfficeShipping()
    {
        $data = array(
            1 => "Pendiente",
            2 => "Compra Aprobada",
            3 => "En Proceso",
            4 => "Listo para Retiro",
            5 => "Entregado"
        );
        return $data;
    }

    public static function getDataStatusOC()
    {
        $data = array(
            1 => "Sin Pagar",
            2 => "Pagado",
            3 => "Pagado Sin Confirmación",
            4 => "Rechazado"
        );
        return $data;
    }

    public static function getDataStatusCotizacion()
    {
        $data = array(
            1 => "Ingresada",
            2 => "Recibida por Ejecutivo",
            3 => "Cotización Cerrada",
            4 => "Rechazada"
        );
        return $data;
    }

    public static function getDataTypeDocument()
    {
        $data = array(
            1 => "Orden de Compra",
            2 => "Cotización"
        );
        return $data;
    }

    public static function getDataTypeDocumentSII()
    {
        $data = array(
            1 => "Boleta",
            2 => "Factura"
        );
        return $data;
    }

    public static function getDataTypeShipping()
    {
        $data = array(
            1 => "Despacho a Domicilio",
            2 => "Retiro en Tienda"
        );
        return $data;
    }

    public static function getDataTypePayment()
    {
        $data = array(
            1 => "Webpay",
            2 => "Transferencia Bancaria",
            3 => "Depósito Bancario",
            4 => "Efectivo",
            5 => "Cotización",
        );
        return $data;
    }

    public static function getTypeDocument($data)
    {
        $array = self::getDataTypeDocument();
        $type_document = $array[$data];
        return $type_document;
    }

    public static function getTypeDocumentSII($data)
    {
        $array = self::getDataTypeDocumentSII();
        $type_document = $array[$data];
        return $type_document;
    }

    public static function getTypeShipping($data)
    {
        $array = self::getDataTypeShipping();
        $type_shippping = $array[$data];
        return $type_shippping;
    }

    public static function getTypePayment($data)
    {
        $array = self::getDataTypePayment();
        $type_payment = $array[$data];
        return $type_payment;

    }

    public static function getStatusShipping($data)
    {
        $array = self::getDataStatusShipping();
        $status_shipping = $array[$data];
        return $status_shipping;
    }

    public static function getStatusOfficeShipping($data)
    {
        $array = self::getDataStatusOfficeShipping();
        $status_shipping = $array[$data];
        return $status_shipping;
    }

    public static function getStatusOC($data)
    {
        $array = self::getDataStatusOC();
        $status_oc = $array[$data];
        return $status_oc;
    }

    public static function getStatusCotizacion($data)
    {
        $array = self::getDataStatusCotizacion();
        $status_cotizacion = $array[$data];
        return $status_cotizacion;
    }

    public static function getColorStatusShipping($data)
    {
        switch ($data) {
            case 1:
                $color = "default";
                break;
            case 2:
                $color = "info";
            case 3:
                $color = "primary";
                break;
            case 4:
                $color = "warning";
                break;
            case 5:
                $color = "success";
                break;
        }
        return $color;
    }

    public static function getColorStatusOC($data)
    {
        switch ($data) {
            case 1:
                $color = "default";
                break;
            case 2:
                $color = "success";
                break;
            case 3:
                $color = "primary";
                break;
            case 4:
                $color = "danger";
                break;
        }
        return $color;
    }

    public static function getColorStatusCotizacion($data)
    {
        switch ($data) {
            case 1:
                $color = "default";
                break;
            case 2:
                $color = "info";
                break;
            case 3:
                $color = "success";
                break;
            case 4:
                $color = "danger";
                break;
        }
        return $color;
    }

    public static function getColorRandom(){
        $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
        $color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
        return $color;
    }

    public static function addResponsiveClassContent($content, $className){

        $content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
        if (!empty($content)) {
            $document = new \DOMDocument();
            libxml_use_internal_errors(true);
            $document->loadHTML(utf8_decode($content), LIBXML_HTML_NODEFDTD);

            $imgs = $document->getElementsByTagName('img');
            foreach ($imgs as $img) {
                $existing_class = $img->getAttribute('class');

                if(!empty($existing_class)){
                    $existing_class_array = explode(' ', $existing_class);

                    if (!in_array($className, $existing_class_array)) {
                        $img->setAttribute('class', "$className $existing_class");
                    }
                } else {
                    $img->setAttribute('class', "$className");
                }
            }

            $html = $document->saveHTML();
            $html = str_replace('<html><body>','',$html);
            $html = str_replace('</body></html>','',$html);

            return $html;
        } else {
            return $content;
        }
    }

    public static function changeAccents($string = ''){
        $string = str_replace('á', '&aacute;', $string);
        $string = str_replace('é', '&eacute;', $string);
        $string = str_replace('í', '&iacute;', $string);
        $string = str_replace('ó', '&oacute;', $string);
        $string = str_replace('ú', '&uacute;', $string);
        $string = str_replace('Á', '&Aacute;', $string);
        $string = str_replace('É', '&Eacute;', $string);
        $string = str_replace('Í', '&Iacute;', $string);
        $string = str_replace('Ó', '&Oacute;', $string);
        $string = str_replace('Ú', '&Uacute;', $string);
        $string = str_replace('ñ', '&ntilde;', $string);
        $string = str_replace('Ñ', '&Ntilde;', $string);
        return $string;
    }

    public static function microtimeFloat(){
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}
?>