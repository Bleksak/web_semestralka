<?php

class Request {
    public static function getRequestMethod() {
        return $_SERVER["REQUEST_METHOD"];
    }

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
                throw new InvalidArgumentException(sprintf("Method {%s} does not exist.", $method));
            }
        }
    }

}