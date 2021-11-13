<?php

namespace helper;

class Header {
    public static function redirect($to) {
        header(sprintf("Location: %s", Server::getScriptDirectory() . $to));
    }
}