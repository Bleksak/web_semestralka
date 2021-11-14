<?php

namespace controller;

use \model\User;

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

    protected function addError($error) {
        $this->twigArray["errors"][] = $error;
    }

    public function __construct() {
        $this->add("base_url", \helper\Server::getScriptDirectory());
        $this->add("user", User::getData());
    }


    public abstract function execute($params = array());
}