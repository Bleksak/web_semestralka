<?php

namespace controller;

class RegisterController extends Controller
{
    public function execute($params = array())
    {
        // read data from POST

        $user = new \model\User();

        if (\helper\Request::getRequestMethod() == "POST") {
            $method = "POST";

            $email = \helper\Request::get("email", $method);
            $firstname = \helper\Request::get("firstname", $method);
            $lastname = \helper\Request::get("lastname", $method);
            $password = \helper\Request::get("password", $method);

            try {
                $user->register($email, $firstname, $lastname, $password);
            } catch (\Exception $e) {
                $this->add("error", $e->getMessage());
            }
        }
    }
}
