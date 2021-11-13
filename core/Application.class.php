<?php

namespace core;

use \controller\Controller;
use \model\Model;


class Application {

    private Model $model;
    private Controller $controller;
    private $view;
    private Router $router;

    public function __construct() {

        $this->router = new Router();

        $config = CONFIG;
        $route = $this->router->getRoute();

        // $modelName = $config["model"][$route];
        $viewName = $config["view"][$route];
        $controllerName = $config["controller"][$route];

        // $this->model = new $modelName();

        $loader = new \Twig\Loader\FilesystemLoader('view');
        $twig = new \Twig\Environment($loader);

        $this->view = $twig->load($viewName);

        $this->controller = new $controllerName();
        $this->controller->execute($this->router->getParams());
    }

    public function __destruct()
    {
        $this->view->display($this->controller->getTwigArray());
    }
}