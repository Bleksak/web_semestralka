<?php

namespace controller;
use \model\User;
use \helper\Header;

use \helper\Request;

class RegisterController extends Controller
{
    public function execute($params = array())
    {
        // read data from POST

        if(User::isLoggedIn()) {
            Header::redirect("/");
        }

        $user = new User();

        if (Request::getRequestMethod() == "POST") {
            $method = "POST";

            $email = Request::get("email", $method);
            $firstname = Request::get("firstname", $method);
            $lastname = Request::get("lastname", $method);
            $password = Request::get("password", $method);

            try {
                $user->register($email, $firstname, $lastname, $password);
            } catch (\Exception $e) {
                $this->add("error", $e->getMessage());
                echo $e->getMessage();
            }
        }
    }
}
