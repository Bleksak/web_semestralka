<?php

/**
 * Server class for managing URLs and files
 * 
 * @author Jiri Velek
 */
class Server {
    private static function get($name) {
        if(!isset($_SERVER[$name])) {
            return "";
        }

        return $_SERVER[$name];
    }

    /**
     * returns request uri
     */
    public static function getRequestURI() {
        return self::get("REQUEST_URI");
    }

    /**
     * returns query string
     */
    public static function getQueryString() {
        return self::get("QUERY_STRING");
    }

    /**
     * returns current script directory
     */
    public static function getScriptDirectory() {
        return dirname(self::get("PHP_SELF"));
    }

    /**
     * returns current route path
     */
    public static function getRoutePath() {
        $uri = self::getRequestURI();
        $dir = preg_quote(self::getScriptDirectory(), "/");

        return preg_replace(["/$dir/", "/\/+/"], ["", "/"], $uri);
    }
}