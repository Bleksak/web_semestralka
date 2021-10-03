<?php

abstract class Controller {
    protected $model, $twig;

    public function __construct($model, $twig) {
        $this->model = $model;
        $this->twig = $twig;
    }

    public abstract function execute($params = array());
}