<?php

class Hash {
    public static function hash($key) {
        return password_hash($key, PASSWORD_DEFAULT);
    }

    public static function verify($key, $hash) {
        return password_verify($key, $hash);
    }
}