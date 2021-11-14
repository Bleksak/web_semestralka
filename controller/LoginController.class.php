<?php

namespace controller;

use \helper\Request;
use \model\User;
use \helper\Header;

class LoginController extends Controller
{
    public function execute($params = array())
    {

        if(User::isLoggedIn()) {
            Header::redirect("/");
        }

        $method = "POST";

        if (Request::getRequestMethod() == $method) {
            $username = Request::get("email", $method);
            $password = Request::get("password", $method);

            $user = new \model\User();

            try {
                $user->login($username, $password);
                Header::redirect("/");
            } catch (\Exception $e) {
                $this->addError($e->getMessage());
            }
        }
    }
}
