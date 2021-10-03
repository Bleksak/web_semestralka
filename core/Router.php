<?php

class Router {

    public function buildRoute() {
        $config = $GLOBALS["config"];
        $base = $config["routes"];
        $uri = explode("/", Server::getRoutePath());
        array_shift($uri);

        if(empty($uri)) {
            $uri = [""];
        }
        
        $route = $base;
        $i = 0;

        while($i < sizeof($uri) && isset($route[$uri[$i]])) {
            $route = $route[$uri[$i]];
            $i += 1;
        }

        if($i == 0) {
            $route = $base["404"];
        }

        if(is_array($route)) {
            if(isset($route[""])) {
                $route = $route[""];
            } else {
                $route = $base["404"];
            }
        }

        $params = array_slice($uri, $i);

        $modelName = $config["model"][$route];
        $viewName = $config["view"][$route];
        $controllerName = $config["controller"][$route];

        

    }

    public function __construct() {
        $this->buildRoute();
    }
}