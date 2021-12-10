<?php

namespace controller;

use helper\File;
use helper\Request;
use model\Article;
use model\User;

class EditController extends Controller {
    public function execute($params = array()) {

        if(!User::isLoggedIn()) {
            return;
        }

        if(empty($params)) {
            return;
        }

        if(!filter_var($params[0], FILTER_VALIDATE_INT)) {
            return;
        }

        $model = new Article();
        $article = $model->get($params[0]);

        if($article === false) {
            return;
        }

        if($article->author !== User::getData()[User::SESSION_ID]) {
            return;
        }

        if($article->approved) {
            return;
        }

        if(Request::getRequestMethod() == "POST") {
            $title = Request::get("title");
            $abstract = Request::get("abstract");

            $update = [
                "title" => $title,
                "abstract" => $abstract
            ];

            if(File::exists("file")) {
                $update["file"] = File::upload("file");
            }

            $model->update($params[0], $update);
        }

        $this->add("article", $article);
        $this->setTitle("Úprava - " . $article->title);
        $this->loadTemplate("edit.twig");
    }
}