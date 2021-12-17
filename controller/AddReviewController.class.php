<?php

namespace controller;

use helper\Header;
use helper\Request;
use helper\Validator;
use model\Article;
use model\Review;
use model\User;
use RuntimeException;
use Twig\Error\RuntimeError;

class AddReviewController extends Controller
{
    public function execute($params = array())
    {
        if (!User::isLoggedIn() || User::getData()[User::SESSION_ROLE] != 2 || empty($params)) {
            return;
        }

        if ((new Article())->get($params[0]) === false) {
            return;
        }

        if (Request::getRequestMethod() == "POST") {

            $text = Request::get("text");
            $model = new Review();
            try {
                $model->add(User::getData()[User::SESSION_ID], $params[0], $text);
                Header::redirect("/articles/");
            } catch (RuntimeException $e) {
                $this->addError($e->getMessage());
            }
        }

        $this->setTitle("Přidat recenzi");
        $this->loadTemplate("addreview.twig");
    }
}
