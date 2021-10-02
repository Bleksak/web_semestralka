<?php

class Server {

    private static function get($name) {
        if(!isset($_SERVER[$name])) {
            return "";
        }

        return $_SERVER[$name];
    }

    public static function getURI() {
        return self::get("REQUEST_URI");
    }

    public static function getQueryString() {
        return self::get("QUERY_STRING");
    }

    public static function getScriptDirectory() {
        return dirname(self::get("PHP_SELF"));
    }
}