<?php

namespace core;

use \controller\Controller;

class Application {

    private Controller $controller;
    // private $view;
    private Router $router;

    public function __construct() {

        $this->router = new Router();

        $config = CONFIG;
        $route = $this->router->getRoute();

        // $viewName = $config["view"][$route];
        $controller= $config["controller"][$route];

        // $loader = new \Twig\Loader\FilesystemLoader('view');
        // $twig = new \Twig\Environment($loader);

        // $this->view = $twig->load("404.twig");

        $this->controller = new $controller();
        $this->controller->execute($this->router->getParams());
    }

    public function __destruct()
    {
        // $this->view->display($this->controller->getTwigArray());
    }
}