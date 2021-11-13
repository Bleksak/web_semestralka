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

    "autoload_folders" => ["controller", "core", "model", "view", "helper"],
    "upload_dir" => "upload",
    "routes" => [
        "" => "home",
        "404" => "404",
        "portal" => "hehe",
        "register" => "register",
        "logout" => "logout"
    ],

    // "model" => [
    //     "home" => "Home",
    //     "404" => "Notfound",
    //     "register" => "User",
    //     "logout" => "User"
    // ],

    "view" => [
        "home" => "home.twig",
        "404" => "404.twig",
        "register" => "register.twig",
        "logout" => "404.twig"
    ],

    "controller" => [
        "home" => \controller\HomeController::class,
        // "404" => "Notfound",
        "register" => \controller\RegisterController::class,
        "logout" => \controller\LogoutController::class
    ]
];

spl_autoload_register(function ($class) {
    foreach (EXT as $ext) {
        $filename = $class . $ext;
        echo $filename . "<br>";

        if (is_file($filename)) {
            require_once $filename;
            break;
        }
    }
});
