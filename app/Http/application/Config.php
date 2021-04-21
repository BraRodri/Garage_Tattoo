<?php
define("VERSION_ANNIO", 2020);
define("VERSION_NUMBER", '2.1.A');

define("MAIL_COLOR_TEXT", "#14920A");
define("MAIL_COLOR_BACKGROUND", "#f6f6f6");

define('URL_FRIENDLY', true);

$URL_FRIENDLY_BASE = (URL_FRIENDLY == true)? '' : 'index.php?url=';

define('URL_FRIENDLY_BASE', $URL_FRIENDLY_BASE);

define('BASE_URL', ($_SERVER['SERVER_NAME'] == 'localhost')? 'http://localhost/RESCATELIFE/admvisch/' : 'https://www.hechoenchile.com/rescatelife/admvisch/');
define('BASE_URL_ROOT', ($_SERVER['SERVER_NAME'] == 'localhost')? 'http://localhost/RESCATELIFE/' : 'https://www.hechoenchile.com/rescatelife/');

define('LOG_GENERATE', false);

define('DEFAULT_CONTROLLER', 'index');
define('DEFAULT_METHOD', 'index');
define('DEFAULT_LAYOUT', 'neon');

define('BASE_URL_ADMIN', BASE_URL . 'public/themes/' . DEFAULT_LAYOUT . '/');
define('UPLOAD_URL', ROOT . 'public' . DS . 'files' . DS);
define('UPLOAD_URL_ROOT', str_replace('\admvisch\\', '', str_replace('/admvisch/', '', ROOT))  . DS . 'upload' . DS);
define('ROOT_WEB', realpath(dirname(__DIR__)) . DS);

define('APP_NAME', 'RESCATELIFE');
define('APP_SLOGAN', 'Gestor de Contenidos');
define('APP_COMPANY', 'RESCATELIFE');

define('SESSION_TIME', 60);
define('SESSION_NAME', 'RESCATELE');
define('SESSION_NAME_FRONT', 'RESCATELE_FRONT');
define('HASH_KEY', '749d50a656fb9');

if($_SERVER['SERVER_NAME'] == 'localhost'){
    $DBHOST = 'localhost';
    $DBNAME = 'ecommerce_rescatelife';
    $DBUSER = 'root';
    $DBPASS = '';
} else {
    $DBHOST = 'localhost';
    $DBNAME = 'hechoenc_rescatelife2020';
    $DBUSER = 'hechoenc_rescusu';
    $DBPASS = 'mVkr!lpbO_Yh';
}

define("DBDRIVER","mysql");
define("DBHOST",$DBHOST);
define("DBNAME",$DBNAME);
define("DBUSER",$DBUSER);
define("DBPASS",$DBPASS);

switch(DBDRIVER)
{
    case 'mysql':
        $DB_GETDATETIME = 'NOW()';
        $DB_GETDATE = 'CURDATE()';
        $DB_GETTIME = 'CURTIME()';
        break;
    case 'mssql':
    case 'sqlsrv':
        $DB_GETDATETIME = 'GETDATE()';
        $DB_GETDATE = 'CONVERT(DATE,GETDATE(),101)';
        $DB_GETTIME = 'CONVERT(DATE,GETDATE(),101)';
        break;
}

define('DB_GETDATETIME', $DB_GETDATETIME);
define('DB_GETDATE', $DB_GETDATE);
define('DB_GETTIME', $DB_GETTIME);

define('DB_AUTOMATIC_DATES', true);

date_default_timezone_set("Chile/Continental");
//date_default_timezone_set("America/New_York");

ini_set('log_errors', 1);
ini_set('error_log', ROOT . 'php-error.log');

define('ENVIRONMENT', 'PRODUCTION'); // DEVELOPMENT or PRODUCTION

define('EMAIL_TEST', 'ignacio@visualchile.com');

define('SHOW_PRICES', true);

define('TIME_TOKEN', '1800');
?>