<?php

namespace controller;

abstract class Controller {
    protected $twigArray = [];

    protected function add($name, $value) {
        $this->twigArray[$name] = $value;
    }

    public function getTwigArray() {
        return $this->twigArray;
    }

    protected function setTitle(string $title) {
        $this->add("page_title", $title);
    }

    public function __construct() {
        $this->add("user_logged", \model\User::isLoggedIn());
    }


    public abstract function execute($params = array());
}