<?php
/**
 * Created by PhpStorm.
 * User: Desarrollo
 * Date: 17-03-2020
 * Time: 18:05
 */
namespace Application;

class Redirect {

    public static function url($url, $httpCode = 301)
    {
        if (!headers_sent()) {
            header("Location:" . $url, TRUE, $httpCode);
            exit(0);
        }

        exit('<meta http-equiv="refresh" content="0; url='.$url.'"/>');
    }

    public static function page($page, $isURLFriendly = false)
    {
        if($isURLFriendly === true && !empty($page) && strpos($page, '?') !== false) {
            $page = Helper::transformURLtoFriendly($page);
        }
        self::url(BASE_URL_ROOT . $page);
    }

    public static function admin($page)
    {
        self::url(BASE_URL . $page);
    }
}