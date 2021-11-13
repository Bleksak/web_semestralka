<?php

namespace controller;

class LoginController extends Controller {
    public function execute($params = array()) {
        // read data from POST

        $user = new \model\User();

        $method = "POST";

        $username = \helper\Request::get("username", $method);
        $password = \helper\Request::get("password", $method);

        try {
            $user->login($username, $password);
            \helper\Header::redirect("/");
        } catch(\Exception $e) {
            $this->add("error", $e->getMessage());
        }
    }
}