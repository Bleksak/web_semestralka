<?php

namespace controller;
use \model\User;
use \helper\Header;

use \helper\Request;
use RuntimeException;

class RegisterController extends Controller
{
    public function execute($params = array())
    {
        if(User::isLoggedIn()) {
            Header::redirect("/");
        }

        $this->setTitle("Registrace");
        $this->loadTemplate("register.twig");

        $user = new User();

        if (Request::getRequestMethod() == "POST") {
            $method = "POST";

            $email = Request::get("email", $method);
            $firstname = Request::get("firstname", $method);
            $lastname = Request::get("lastname", $method);
            $password = Request::get("password", $method);

            try {
                $user->register($email, $firstname, $lastname, $password);
                Header::redirect("/");
            } catch (RuntimeException $e) {
                $this->addError($e->getMessage());
            }
        }
    }
}
