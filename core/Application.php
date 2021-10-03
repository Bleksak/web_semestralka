<?php

class Application {

    private $model, $controller, $view, $router;

    public function __construct() {

        $this->router = new Router();
        
    }
}