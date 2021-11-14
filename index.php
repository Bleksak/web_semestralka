<?php

require_once "Config.php";

\helper\Session::start();

$app = new \core\Application();

// $user = new \model\User();

// $user->login("asdf@asdf.com", "asd");
// $user->register("asdf@asdf.com", "Jiri", "Velek", "asd");

// if (Request::getRequestMethod() == "POST") {
//     if (File::upload("file", "pdf")) {
//         echo "ok";
//     }
// }


// $loader = new \Twig\Loader\FilesystemLoader("view");
// $twig = new \Twig\Environment($loader);

// $view = $twig->load("upload.twig");
// $view->display();
