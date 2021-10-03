<?php

// we don't want cookies mixed with POST and GET data
ini_set("request_order", "PG");

$GLOBALS["config"] = [
    "database" => [
        "hostname" => "127.0.0.1",
        "username" => "root",
        "password" => "",
        "port" => 3306
    ],

    "autoload_folders" => ["controller", "core", "model", "view", "helper"],
    "routes" => [
        "" => "home",
        "404" => "404",
        "portal" => "hehe"
    ],

    "model" => [
        "home" => "Home",
        "404" => "Notfound"
    ],

    "view" => [
        "home" => "home.twig",
        "404" => "404.twig"
    ],

    "controller" => [
        "home" => "Home",
        "404" => "Notfound"
    ]
];

spl_autoload_register(function($class) {
    $filename = strtolower($class) . ".php";

    foreach($GLOBALS["config"]["autoload_folders"] as $folder) {

        $path = $folder . "/" . $filename;

        if(is_file($path)) {
            require_once $path;
            return;
        }
    }
});
