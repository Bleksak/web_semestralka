<?php

require_once "vendor/autoload.php";

// we don't want cookies mixed with POST and GET data
ini_set("request_order", "PG");

const EXT = [".class.php", ".interface.php"];

const CONFIG = [
    "database" => [
        "hostname" => "127.0.0.1",
        "username" => "root",
        "password" => "",
        "port" => 3306,
        "dbname" => "konference"
    ],

    "upload_dir" => "upload",

    "routes" => [
        "" => "home",
        "404" => "404",
        "register" => "register",
        "logout" => "logout",
        "login" => "login",
        "new" => "newarticle"
    ],

    "view" => [
        "home" => "home.twig",
        "404" => "404.twig",
        "register" => "register.twig",
        "logout" => "404.twig",
        "login" => "login.twig",
        "newarticle" => "newarticle.twig"
    ],

    "controller" => [
        "home" => \controller\HomeController::class,
        "404" => \controller\NotfoundController::class,
        "register" => \controller\RegisterController::class,
        "logout" => \controller\LogoutController::class,
        "login" => \controller\LoginController::class,
        "newarticle" => \controller\NewArticleController::class
    ]
];

spl_autoload_register(function ($class) {
    foreach (EXT as $ext) {
        $filename = $class . $ext;

        if (is_file($filename)) {
            require_once $filename;
            break;
        }
    }
});
