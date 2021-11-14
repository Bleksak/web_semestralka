<?php

namespace controller;

use helper\File;
use helper\Header;
use helper\Request;
use model\Article;
use model\User;
use RuntimeException;

class NewArticleController extends Controller {
    public function execute($params = array()) {

        if(!User::isLoggedIn()) {
            Header::redirect("/");
        }

        if(Request::getRequestMethod() == "POST") {
            $title = Request::get("title");
            $abstract = Request::get("abstract");
            $user = User::getData()[User::SESSION_ID];

            $article = new Article();

            try {
                $filename = File::upload("file");
                $article->create($user, $title, $abstract, $filename);
            } catch (\RuntimeException $e) {
                $this->addError($e->getMessage());
            }
        }
    }
}