<?php

namespace core;

class Router
{

    // private Controller $controller;

    private string $route;
    private array $params;

    public function createController()
    {
        $config = CONFIG;
        $base = $config["routes"];
        $uri = explode("/", \helper\Server::getRoutePath());
        array_shift($uri);

        if (empty($uri)) {
            $uri = [""];
        }

        $route = $base;
        $i = 0;

        while ($i < sizeof($uri) && isset($route[$uri[$i]])) {
            $route = $route[$uri[$i]];
            $i += 1;
        }

        if ($i == 0) {
            $route = $base["404"];
        }

        if (is_array($route)) {
            if (isset($route[""])) {
                $route = $route[""];
            } else {
                $route = $base["404"];
            }
        }

        $this->route = $route;
        $this->params = array_slice($uri, $i);
    }

    public function __construct()
    {
        $this->createController();
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
