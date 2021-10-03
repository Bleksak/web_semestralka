<?php

class LoginController extends Controller {
    public function execute($params = array()) {
        // read data from POST

        $method = "POST";

        $username = Request::get("username", $method);
        $password = Request::get("password", $method);

        $this->model->login($username, $password);
    }
}