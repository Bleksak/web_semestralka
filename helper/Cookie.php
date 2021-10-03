<?php

/**
 * Class Cookie for setting and removing cookies
 * @author Jiri Velek
 */
class Cookie {
    /**
     * sets a cookie
     * 
     * @param $name name of the cookie
     * @param $value value of the cookie
     * @param $time time in minutes after which the cookie will expire
     */
    public static function set($name, $value, $time) {
        setcookie($name, $value, time() + $time * 60);
    }

    /**
     * deletes a cookie
     * 
     * @param $name name of the cookie to be removed
     */
    public static function delete($name) {
        self::set($name, "", -1);
    }

    /**
     * returns a cookie value
     * 
     * @param $name name of the cookie
     */
    public static function get($name) {
        if(!isset($_COOKIE[$name])) {
            return null;
        }

        return $_COOKIE[$name];
    }
}