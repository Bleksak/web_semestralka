<?php

namespace controller;

class LogoutController extends Controller {
    public function execute($params = array()) {
        $this->setTitle("Odhlášení");
        $user = new \model\User();
        $user->logout();
        \helper\Header::redirect("/");
    }
}