<?php

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
        if(!isset($_SESSION[$name])) {
            return null;
        }

        return $_SESSION[$name];
    }
}