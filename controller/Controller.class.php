<?php

namespace controller;

use \model\User;

abstract class Controller {
    private const DEFAULT_TEMPLATE = "404.twig";

    private $twigArray = [];
    private $view;

    protected function add($name, $value) {
        $this->twigArray[$name] = $value;
    }

    protected function setTitle(string $title) {
        $this->add("page_title", $title);
    }

    protected function addError($error) {
        $this->twigArray["errors"][] = $error;
    }

    protected function loadTemplate(string $template) {
        $loader = new \Twig\Loader\FilesystemLoader("view");
        $twig = new \Twig\Environment($loader);

        $this->view = $twig->load($template);
    }

    public function __construct() {
        $this->add("base_url", \helper\Server::getScriptDirectory());
        $this->add("user", User::getData());
        $this->setTitle("Stránka nenalezena");
        $this->loadTemplate(self::DEFAULT_TEMPLATE);
    }

    public abstract function execute($params = array());

    public function __destruct() {
        // tohle nefunguje, protoze php zmeni v destructoru cwd jinam (bug uz z roku 2005..) 
        // a twig nastavuje cestu sablon pomoci cwd
        // (https://bugs.php.net/bug.php?id=31570), (https://bugs.php.net/bug.php?id=34206)

        // if($this->view == NULL) {
        //     $this->loadTemplate(self::DEFAULT_TEMPLATE);
        // }

        $this->view->display($this->twigArray);
    }
}