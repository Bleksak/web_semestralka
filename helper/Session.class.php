<?php

namespace helper;

class Session {
    public static function start() {
        session_start();
    }

    public static function destroy() {
        session_destroy();
    }

    public static function set($name, $value) {
        $_SESSION[$name] = $value;
    }

    public static function get($name) {
        if(!self::exists($name)) {
            return null;
        }

        return $_SESSION[$name];
    }

    public static function unset($name) {
        unset($_SESSION[$name]);
    }
    
    public static function exists($name) {
        return isset($_SESSION[$name]);
    }
}