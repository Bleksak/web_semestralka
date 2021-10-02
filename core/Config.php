<?php

$GLOBALS["config"] = [
    "database" => [
        "hostname" => "127.0.0.1",
        "username" => "root",
        "password" => "",
        "port" => 3306
    ]
];

spl_autoload_register(function($class) {
    $filename = strtolower($class) . ".php";
    $folders = ["controller", "core", "model", "view"];

    foreach($folders as $folder) {

        $path = $folder . "/" . $filename;

        if(is_file($path)) {
            require_once $path;
            return;
        }
    }
});
