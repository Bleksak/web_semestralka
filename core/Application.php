<?php

class Application {

    private Model $model;
    private Controller $controller;
    private $view;
    private Router $router;

    public function __construct() {

        $this->router = new Router();

        $config = $GLOBALS["config"];
        $route = $this->router->getRoute();

        $modelName = $config["model"][$route];
        $viewName = $config["view"][$route];
        $controllerName = $config["controller"][$route];

        // create instance of controller, provide:
        // model instance
        // TODO: twig instnance !!
        // all params from url
        // 
        // when controller is done => render everything

        $model = new $modelName();

        // TODO: add twig
        $twig = null;

        $this->controller = new $controllerName($model, $twig);
        $this->controller->execute($this->router->getParams());
    }
}