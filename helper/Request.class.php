<?php

namespace helper;

/**
 * Request class for handling GET and POST requests
 * 
 * @author Jiri Velek
 */
class Request {

    /**
     * Returns server request method (POST, GET)
     */

    public static function getRequestMethod() : string {
        return $_SERVER["REQUEST_METHOD"];
    }

    /**
     * returns POST or GET value
     * 
     * @param $key the name of the variable
     * @param $method the method used (GET/POST)
     */
    public static function get($key, $method = null) {
        if(!isset($_REQUEST[$key])) {
            return null;
        }

        switch($method) {
            case "GET": {
                return filter_input(INPUT_GET, $key);
            } break;

            case "POST": {
                return filter_input(INPUT_POST, $key);
            } break;

            case null: {
                return $_REQUEST[$key];
            } break;

            default: {
                throw new \InvalidArgumentException(sprintf("Method {%s} does not exist.", $method));
            }
        }
    }
}